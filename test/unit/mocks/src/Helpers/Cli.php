<?php

namespace BfwCli\Test\Mock\Helpers;

/**
 * Mock for Helpers\Cli class
 */
class Cli extends \BfwCli\Helpers\Cli
{
    /**
     * Call the protected method colorForShell
     * @see \BfwCli\Helpers\Cli::colorForShell()
     */
    public static function colorForShell(string $color, string $type): int
    {
        return parent::colorForShell($color, $type);
    }

    /**
     * Call the protected method styleForShell
     * @see \BfwCli\Helpers\Cli::styleForShell()
     */
    public static function styleForShell(string $style): int
    {
        return parent::styleForShell($style);
    }
}
