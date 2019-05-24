<?php

//Create src/cli directory
echo "\n".'>> Create src/cli directory ... ';

if (file_exists(SRC_DIR.'cli/')) {
    echo "\033[1;33mAlready exist.\033[0m\n";
    return;
}
    
if (mkdir(SRC_DIR.'cli/', 0755)) {
    echo "\033[1;32mCreated.\033[0m\n";
    return;
}

//If error during the directory creation
trigger_error(
    'Module '.$this->name.' install error : Fail to create /src/cli/ directory',
    E_USER_WARNING
);
echo "\033[1;31mFail.\033[0m\n";
