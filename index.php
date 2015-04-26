<?php

    /*
    * Index all requests will submit on index.php. It's an entry point to application
    */

    // Define base directory path
    define ('BASE_PATH', dirname(realpath(__FILE__)));

    // Require common settings for application
    require_once BASE_PATH.'/config/config.php';

    function __autoload($class){
        require_once BASE_PATH . "/core/$class.php";
    }

    $init = new Init();