<?php

namespace BfwCli\test\unit;

use \atoum;

$vendorPath = realpath(__DIR__.'/../../../vendor');
require_once($vendorPath.'/autoload.php');
require_once($vendorPath.'/bulton-fr/bfw/test/unit/helpers/Application.php');
require_once($vendorPath.'/bulton-fr/bfw/test/unit/mocks/src/Subject.php');

/**
 * @engine isolate
 */
class BfwCli extends atoum
{
    use \BFW\Test\Helpers\Application;
    use \BfwCli\Test\Helpers\Module;
    
    protected $mock;
    
    public function beforeTestMethod($testMethod)
    {
        $this->setRootDir(__DIR__.'/../../..');
        $this->createApp();
        $this->initApp();
        $this->removeLoadModules();
        $this->createModule();
        $this->app->run();
        
        if ($testMethod === 'testConstructAndGetters') {
            return;
        }
        
        $this->mockGenerator
            ->makeVisible('run')
            ->makeVisible('checkFile')
            ->makeVisible('execFile')
            ->generate('BfwCli\BfwCli')
        ;
        
        $this->mock = new \mock\BfwCli\BfwCli($this->module);
    }
    
    public function testConstructAndGetters()
    {
        $this->assert('test BfwCli::__construct')
            ->object($bfwCli = new \BfwCli\BfwCli($this->module))
                ->isInstanceOf('\SplObserver')
        ;
        
        $this->assert('test BfwCli::getters')
            ->object($bfwCli->getModule())
                ->isIdenticalTo($this->module)
            ->string($bfwCli->getExecutedFile())
                ->isEmpty()
        ;
    }
    
    public function testUpdate()
    {
        $this->assert('test BfwCli::update - prepare')
            ->given($subject = new \BFW\Test\Mock\Subject)
        ;
        
        $this->assert('test BfwCli::update for searchRoute system')
            ->given($subject = new \BFW\Test\Mock\Subject)
            ->and($subject->setAction('BfwApp_done_ctrlRouterLink'))
            ->and($subject->setContext($this->app->getCtrlRouterLink()))
            ->then
            ->if($this->calling($this->mock)->run = null)
            ->then
            ->variable($this->mock->update($subject))
                ->isNull()
            ->mock($this->mock)
                ->call('run')
                    ->once()
        ;
    }
    
    public function testObtainFileFromArg()
    {
        $this->assert('test BfwCli::obtainFileFromArg without arg')
            ->if($this->function->getopt = [])
            ->then
            ->exception(function() {
                $this->mock->obtainFileFromArg();
            })
                ->hasCode(\BfwCli\BfwCli::ERR_NO_FILE_SPECIFIED_IN_ARG)
        ;
        
        $this->assert('test BfwCli::obtainFileFromArg without arg')
            ->if($this->function->getopt = [
                'f' => 'unitTestCli'
            ])
            ->then
            ->string($this->mock->obtainFileFromArg())
                ->isEqualTo(CLI_DIR.'unitTestCli.php')
        ;
    }
    
    public function testRunAndGetExecutedFile()
    {
        $this->assert('test BfwCli::run - prepare')
            ->and($this->calling($this->mock)->execFile = true)
            ->then
        ;
        
        $this->assert('test BfwCli::run with checkFile fail')
            ->if($this->calling($this->mock)->checkFile = false)
            ->and($this->calling($this->mock)->obtainFileFromArg = 'unitTestCli.php')
            ->then
            ->variable($this->mock->run())
                ->isNull()
            ->mock($this->mock)
                ->call('execFile')
                    ->never()
            ->string($this->mock->getExecutedFile())
                ->isEqualTo('unitTestCli.php')
        ;
        
        $this->assert('test BfwCli::run with checkFile success')
            ->if($this->calling($this->mock)->checkFile = true)
            ->and($this->calling($this->mock)->obtainFileFromArg = 'unitTestCli2.php')
            ->then
            ->variable($this->mock->run())
                ->isNull()
            ->mock($this->mock)
                ->call('execFile')
                    ->once()
            ->string($this->mock->getExecutedFile())
                ->isEqualTo('unitTestCli2.php')
        ;
    }
    
    public function testCheckFile()
    {
        $this->assert('test BfwCli::checkFile if file not exist')
            ->if($this->function->file_exists = false)
            ->then
            ->exception(function() {
                $this->mock->checkFile();
            })
                ->hasCode(\BfwCli\BfwCli::ERR_FILE_NOT_FOUND)
        ;
        
        $this->assert('test BfwCli::checkFile if file exist')
            ->if($this->function->file_exists = true)
            ->then
            ->boolean($this->mock->checkFile())
                ->isTrue()
        ;
    }
    
    public function testExecFile()
    {
        //Require not mockable, so we can't test with file to execute.
    }
}
