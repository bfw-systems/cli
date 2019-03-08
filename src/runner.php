<?php

$this->monolog = new \BFW\Monolog(
    'bfw-cli',
    \BFW\Application::getInstance()->getConfig()
);
$this->monolog->addAllHandlers();

$bfwCli = new \BfwCli\BfwCli($this);

$app        = \BFW\Application::getInstance();
$appSubject = $app->getSubjectList()->getSubjectByName('ctrlRouterLink');
$appSubject->attach($bfwCli);

$this->monolog
    ->getLogger()
    ->debug('Add CLI_DIR constant and \Cli namespace.');

\BFW\Helpers\Constants::create('CLI_DIR', SRC_DIR.'cli/');
$app->getComposerLoader()->addPsr4('Cli\\', CLI_DIR);
