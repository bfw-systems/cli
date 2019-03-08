<?php

namespace BfwCli;

use \Exception;

class BfwCli implements \SplObserver
{
    /**
     * @const ERR_NO_FILE_SPECIFIED_IN_ARG Exception code if the cli file to
     * run is not specified with the "-f" argument.
     */
    const ERR_NO_FILE_SPECIFIED_IN_ARG = 1201001;
    
    /**
     * @const ERR_CLI_FILE_NOT_FOUND Exception code if the cli file to run is
     * not found.
     */
    const ERR_FILE_NOT_FOUND = 1201002;
    
    /**
     * @var \BFW\Module $module The bfw module instance for this module
     */
    protected $module;
    
    /**
     * @var string The name of the executed cli file
     */
    protected $executedFile = '';
    
    /**
     * Constructor
     * 
     * @param \BFW\Module $module
     */
    public function __construct(\BFW\Module $module)
    {
        $this->module = $module;
    }
    
    /**
     * Getter accessor for module property
     * 
     * @return \BFW\Module
     */
    public function getModule(): \BFW\Module
    {
        return $this->module;
    }
    
    /**
     * Getter to the property executedFile
     * 
     * @return string
     */
    public function getExecutedFile(): string
    {
        return $this->executedFile;
    }
    
    /**
     * Observer update method
     * 
     * @param \SplSubject $subject
     * 
     * @return void
     */
    public function update(\SplSubject $subject)
    {
        if ($subject->getAction() === 'BfwApp_done_ctrlRouterLink') {
            $this->run();
        }
    }
    
    /**
     * Check if the system is run in cli mode, and call methods to find cli
     * file to execute and execute it.
     * 
     * @return void
     */
    protected function run()
    {
        if (PHP_SAPI !== 'cli') {
            return;
        }
        
        \BFW\Application::getInstance()
            ->getSubjectList()
            ->getSubjectByName('ApplicationTasks')
            ->sendNotify('run_cli_file');
        
        $this->executedFile = $this->obtainFileFromArg();
        
        if ($this->checkFile() === true) {
            $this->execFile();
        }
    }
    
    /**
     * Obtain the file to execute from the cli arg.
     * Search the arg "f" to get the value.
     * 
     * @return string The file path
     * 
     * @throws \Exception If no file is declared to be executed
     */
    public function obtainFileFromArg(): string
    {
        $cliArgs = getopt('f:');
        if (!isset($cliArgs['f'])) {
            throw new Exception(
                'Error: No file specified.',
                $this::ERR_NO_FILE_SPECIFIED_IN_ARG
            );
        }

        return CLI_DIR.$cliArgs['f'].'.php';
    }
    
    /**
     * Check the file to execute.
     * 
     * @return boolean
     * 
     * @throws \Exception
     */
    protected function checkFile(): bool
    {
        if (!file_exists($this->executedFile)) {
            throw new Exception(
                'File to execute not found.',
                $this::ERR_FILE_NOT_FOUND
            );
        }
        
        return true;
    }
    
    /**
     * Execute the cli file into a different scope.
     * The new scope have access to $this of this class.
     * 
     * @return void
     */
    protected function execFile()
    {
        $this->module
            ->monolog
            ->getLogger()
            ->debug(
                'execute cli file.',
                ['file' => $this->executedFile]
            );
        
        $fctRunCliFile = function() {
            require($this->executedFile);
        };
        
        $fctRunCliFile();
    }
}
