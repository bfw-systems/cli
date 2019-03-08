<?php

namespace BfwCli\Test\Helpers;

$vendorPath = realpath(__DIR__.'/../../../vendor');
require_once($vendorPath.'/bulton-fr/bfw/test/unit/helpers/Application.php');
require_once($vendorPath.'/bulton-fr/bfw/test/unit/mocks/src/Module.php');

trait Module
{
    protected $module;
    
    protected function removeLoadModules()
    {
        $runTasks = $this->app->getRunTasks();
        $allSteps = $runTasks->getRunSteps();
        unset($allSteps['moduleList']);
        $runTasks->setRunSteps($allSteps);
    }
    
    protected function createModule()
    {
        $config     = new \BFW\Config('bfw-cli');
        $moduleList = $this->app->getModuleList();
        $moduleList->setModuleConfig('bfw-cli', $config);
        $moduleList->addModule('bfw-cli');
        
        $this->module = $moduleList->getModuleByName('bfw-cli');
        
        $this->module->monolog = new \BFW\Monolog(
            'bfw-cli',
            \BFW\Application::getInstance()->getConfig()
        );
        $this->module->monolog->addAllHandlers();
        
        \BFW\Helpers\Constants::create('CLI_DIR', SRC_DIR.'cli/');
        \BFW\Application::getInstance()
            ->getComposerLoader()
            ->addPsr4('Cli\\', CLI_DIR)
        ;
    }
}
