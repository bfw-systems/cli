<?php

namespace BfwCli\Helpers\test\unit;

use \atoum;

$vendorPath = realpath(__DIR__.'/../../../../vendor');
require_once($vendorPath.'/autoload.php');
require_once($vendorPath.'/bulton-fr/bfw/test/unit/helpers/Application.php');
require_once($vendorPath.'/bulton-fr/bfw/test/unit/helpers/OutputBuffer.php');

/**
 * @engine isolate
 */
class Cli extends atoum
{
    use \BFW\Test\Helpers\Application;
    use \BFW\Test\Helpers\OutputBuffer;
    
    public function beforeTestMethod($testMethod)
    {
        $this->setRootDir(__DIR__.'/../../../..');
        $this->createApp();
        $this->initApp();
        
        /*
         * Not allowed on static method -_-
         * 
        $this->mockGenerator
            ->makeVisible('colorForShell')
            ->makeVisible('styleForShell')
            ->generate('BFW\Helpers\Cli')
        ;
        */
    }
    
    public function testColorForShell()
    {
        $this->assert('test Helpers\Cli::colorForShell with not existing color')
            ->exception(function() {
                \BfwCli\Test\Mock\Helpers\Cli::colorForShell('atoum', 'bg');
            })
                ->hasCode(\BfwCli\Helpers\Cli::ERR_COLOR_NOT_AVAILABLE)
        ;
        
        $this->assert('test Helpers\Cli::colorForShell for background color')
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('black', 'bg'))
                ->isEqualTo(40)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('red', 'bg'))
                ->isEqualTo(41)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('green', 'bg'))
                ->isEqualTo(42)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('yellow', 'bg'))
                ->isEqualTo(43)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('blue', 'bg'))
                ->isEqualTo(44)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('magenta', 'bg'))
                ->isEqualTo(45)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('cyan', 'bg'))
                ->isEqualTo(46)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('white', 'bg'))
                ->isEqualTo(47)
        ;
        
        $this->assert('test Helpers\Cli::colorForShell for text color')
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('black', 'txt'))
                ->isEqualTo(30)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('red', 'txt'))
                ->isEqualTo(31)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('green', 'txt'))
                ->isEqualTo(32)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('yellow', 'txt'))
                ->isEqualTo(33)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('blue', 'txt'))
                ->isEqualTo(34)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('magenta', 'txt'))
                ->isEqualTo(35)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('cyan', 'txt'))
                ->isEqualTo(36)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::colorForShell('white', 'txt'))
                ->isEqualTo(37)
        ;
    }
    
    public function testStyleForShell()
    {
        $this->assert('test Helpers\Cli::styleForShell with not existing style')
            ->exception(function() {
                \BfwCli\Test\Mock\Helpers\Cli::styleForShell('atoum');
            })
                ->hasCode(\BfwCli\Helpers\Cli::ERR_STYLE_NOT_AVAILABLE)
        ;
        
        $this->assert('test Helpers\Cli::styleForShell with existing style')
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('normal'))
                ->isEqualTo(0)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('bold'))
                ->isEqualTo(1)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('not-bold'))
                ->isEqualTo(21)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('underline'))
                ->isEqualTo(4)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('not-underline'))
                ->isEqualTo(24)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('blink'))
                ->isEqualTo(5)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('not-blink'))
                ->isEqualTo(25)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('reverse'))
                ->isEqualTo(7)
            ->integer(\BfwCli\Test\Mock\Helpers\Cli::styleForShell('not-reverse'))
                ->isEqualTo(27)
        ;
    }
    
    public function testDisplayMsgWithAutoFlush()
    {
        $this
            ->given($lastFlushedMsg = '')
            ->if($this->defineOutputBuffer($lastFlushedMsg))
        ;
        
        $this->assert('check Helpers\Cli::displayMsg flush status')
            ->variable(\BfwCli\Helpers\Cli::$callObFlush)
                ->isEqualTo(\BfwCli\Helpers\Cli::FLUSH_AUTO)
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with default parameters')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)'))
            ->string($lastFlushedMsg)
                ->isEqualTo('unit test with atoum :)')
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with a text color')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)', 'green'))
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[0;32munit test with atoum :)\033[0m")
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with a text color and a text style')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)', 'green', 'bold'))
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[1;32munit test with atoum :)\033[0m")
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with a text and background color and with a text style')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)', 'green', 'bold', 'white'))
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[1;32;47munit test with atoum :)\033[0m")
        ;
    }
    
    public function testDisplayMsgWithManualFlush()
    {
        $this
            ->given($lastFlushedMsg = '')
            ->if($this->defineOutputBuffer($lastFlushedMsg))
        ;
        
        $this->assert('change Helpers\Cli::displayMsg flush status')
            ->if(\BfwCli\Helpers\Cli::$callObFlush = \BfwCli\Helpers\Cli::FLUSH_MANUAL)
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with default parameters')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)'))
            ->string($lastFlushedMsg)
                ->isEmpty()
            
            ->then
            ->if(ob_flush())
            ->string($lastFlushedMsg)
                ->isEqualTo('unit test with atoum :)')
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with a text color')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)', 'green'))
            ->string($lastFlushedMsg)
                ->isEmpty()
            
            ->then
            ->if(ob_flush())
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[0;32munit test with atoum :)\033[0m")
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with a text and a text style')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)', 'green', 'bold'))
            ->string($lastFlushedMsg)
                ->isEmpty()
            
            ->then
            ->if(ob_flush())
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[1;32munit test with atoum :)\033[0m")
        ;
        
        $this->assert('test Helpers\Cli::displayMsg with a text and background color and with a text style')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsg('unit test with atoum :)', 'green', 'bold', 'white'))
            ->string($lastFlushedMsg)
                ->isEmpty()
            
            ->then
            ->if(ob_flush())
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[1;32;47munit test with atoum :)\033[0m")
        ;
    }
    
    public function testDisplayMsgNL()
    {
        $this
            ->given($lastFlushedMsg = '')
            ->if($this->defineOutputBuffer($lastFlushedMsg))
        ;
        
        $this->assert('change Helpers\Cli::displayMsgNL flush status to auto')
            ->if(\BfwCli\Helpers\Cli::$callObFlush = \BfwCli\Helpers\Cli::FLUSH_AUTO)
        ;
        
        $this->assert('test Helpers\Cli::displayMsgNL with default parameters')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsgNL('unit test with atoum :)'))
            ->string($lastFlushedMsg)
                ->isEqualTo('unit test with atoum :)'."\n")
        ;
        
        $this->assert('test Helpers\Cli::displayMsgNL with a text color')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsgNL('unit test with atoum :)', 'green'))
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[0;32munit test with atoum :)\n\033[0m")
        ;
        
        $this->assert('test Helpers\Cli::displayMsgNL with a text color')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsgNL('unit test with atoum :)', 'green', 'bold', 'white'))
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[1;32;47munit test with atoum :)\n\033[0m")
        ;
        
        $this->assert('change Helpers\Cli::displayMsg flush status to manual')
            ->if(\BfwCli\Helpers\Cli::$callObFlush = \BfwCli\Helpers\Cli::FLUSH_MANUAL)
        ;
        
        $this->assert('test Helpers\Cli::displayMsgNL not flushed with default parameters')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsgNL('unit test with atoum :)'))
            ->string($lastFlushedMsg)
                ->isEmpty()
            
            ->then
            ->if(ob_flush())
            ->string($lastFlushedMsg)
                ->isEqualTo('unit test with atoum :)'."\n")
        ;
        
        $this->assert('test Helpers\Cli::displayMsgNL not flushed with a text color')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsgNL('unit test with atoum :)', 'green'))
            ->string($lastFlushedMsg)
                ->isEmpty()
            
            ->then
            ->if(ob_flush())
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[0;32munit test with atoum :)\n\033[0m")
        ;
        
        $this->assert('test Helpers\Cli::displayMsgNL not flushed with a text color')
            ->given($lastFlushedMsg = '')
            ->if(\BfwCli\Helpers\Cli::displayMsgNL('unit test with atoum :)', 'green', 'bold', 'white'))
            ->string($lastFlushedMsg)
                ->isEmpty()
            
            ->then
            ->if(ob_flush())
            ->string($lastFlushedMsg)
                ->isEqualTo("\033[1;32;47munit test with atoum :)\n\033[0m")
        ;
    }
}
