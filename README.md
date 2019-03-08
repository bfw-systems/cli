# bfw-cli

[![Build Status](https://travis-ci.org/bulton-fr/bfw-clii.svg?branch=2.0)](https://travis-ci.org/bulton-fr/bfw-cli) [![Coverage Status](https://coveralls.io/repos/github/bulton-fr/bfw-cli/badge.svg?branch=2.0)](https://coveralls.io/github/bulton-fr/bfw-cli?branch=2.0) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bulton-fr/bfw-cli/badges/quality-score.png?b=2.0)](https://scrutinizer-ci.com/g/bulton-fr/bfw-cli/?branch=2.0)
[![Latest Stable Version](https://poser.pugx.org/bulton-fr/bfw-cli/v/stable)](https://packagist.org/packages/bulton-fr/bfw-cli) [![License](https://poser.pugx.org/bulton-fr/bfw-cli/license)](https://packagist.org/packages/bulton-fr/bfw-cli)

Module to use the BFW framework with basic cli script.
A futur module will be created for an advanced usage of cli scripts.

---

## Install

You can use composer to get the module : `composer require bulton-fr/bfw-cli @stable`

And to install the module : `./vendor/bin/bfwInstallModules`

## Config

There is no config file :smile:

## Use it

After installed it, just use the `cli.php` script into you console.  
Use the parameter `-f` to say what file into `/src/cli` to execute.  
The parameters `-f` is mandatory for `/cli.php` script, but all cli script executed by him can also have their own parameters.

Note : We use the function [getopt()](http://php.net/manual/en/function.getopt.php), so take the time to understand how to pass parameters values.

### Example

An example script is installed by default, you can use it to see how execute cli script.  
To play the script, you should do :

```
$ php cli.php -f=exemple
CLI Exemple file
```

```
$ php cli.php -f=exemple -h
CLI Exemple file

Helping Informations : Parameters script
* -v --version : Version of test script
* -p --parameters : Display args array
* -h --help : View this message

$ php cli.php -f=exemple -p
CLI Exemple file
Array
(
    [f] => exemple
    [p] => 
)
```

## Helper

An helper to sent message into the console is present.

To write a message, you can use methods `displayMsg` and `displayMsgNL`.
The first write a message without a line break. The second adds a line break automatically at the end of the message.

### Flush system

By default, when the framework is loaded, the function [ob_start](http://php.net/manual/en/function.ob-start.php) is called.
Because of that, all output is sent into a buffer and displayed when application shutdown.
To avoid that, you can force the buffer to display with the function [ob_flush](http://php.net/manual/en/function.ob-flush.php).

In cli, it can be useful to always display the buffer content when we write a message.
But sometimes, we can prefer the message is not displayed right now.

To manage this two case, a flush system is integrated.
By default the buffer is flushed at the end of methods `displayMsg` and `displayMsgNL`.
But you can define to not flush and choose when you want to flush the buffer.

For doing that, there are :
* constants :
  * `const FLUSH_AUTO = 'auto';`
  * `const FLUSH_MANUAL = 'manual';`
* property `public static $callObFlush = self::FLUSH_AUTO;`

It's the property value who defines if the flush will be automatically called or not.
If the value is the constant `FLUSH_AUTO`, the flush will be done at the end of methods.
But if the value is the constant `FLUSH_MANUAL`, the flush will never be done by the system; you should do it yourself.

### Constants

#### Errors/Exception codes

These constants are sent when an exception is thrown and used like exception code.

__`ERR_COLOR_NOT_AVAILABLE`__

Exception code if the color is not available.

__`ERR_STYLE_NOT_AVAILABLE`__

Exception code if the style is not available.

### Methods

#### To display a message

__`public static function displayMsg(string $msg, string $colorTxt = 'white', string $style = 'normal', string $colorBg = 'black')`__

__`public static function displayMsgNL(string $msg, string $colorTxt = 'white', string $style = 'normal', string $colorBg = 'black')`__

These two methods will display a message with color and/or style.

The first method will just display the message, the second will add a line break (`\n`) at the end of the message.

Arguments are :
* `string $msg` : It's the message to display.
* `string $colorTxt` : It's the color of the text. Refer to the method `colorForShell` to know available color.
* `string $style` : It's the style of the text. Refer to the method `styleForShell` to know available color.
* `string $colorBg` : It's the background color to use. Refer to the method `colorForShell` to know available color.

If there is only the first argument, the color and style will not be defined and stay with shell configuration at this moment.
And if there are only the first three arguments, the background-color will not be defined and stay with shell configuration at this moment.

#### To define color code to use in the shell

__`protected static function colorForShell(string $color, string $type): int`__

This method will return the color code to use in the shell for a string color name.

Available colors are :
* black
* red
* green
* yellow
* blue
* magenta
* cyan
* white

If the color name into the argument not exist, an exception will be thrown.
The exception code will be the constant `\BfwCli\Helpers\Cli::ERR_COLOR_NOT_AVAILABLE`.

The argument `string $type` is where the color code will be used.
The value can be `txt` or `bg`.

It's to return the correct color code.
To explain, each color has an integer value.
But if the color is for the text color, the value should be between 30 and 39.
And if the color is for the background color, the value should be between 40 and 49.

So we ask where the color will be used to return to the correct value range.

#### To define style code to use in the shell

__`protected static function styleForShell(string $style): int`__

This method will return the style code to use in the shell for a string style name.

Available styles are :
* normal
* bold
* not-bold
* underline
* not-underline
* blink
* not-blink
* reverse
* not-reverse

if the style name into the argument not exist, an exception will be thrown.
The exception code will be the constant `\BfwCli\Helpers\Cli::ERR_STYLE_NOT_AVAILABLE`.

