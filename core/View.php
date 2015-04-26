<?php

/*
 * View Class extended with Helper
 */

class View extends Helper {

    public function render($name) {
        global $config;
        require_once $config['ftp_views']. '/header.php';
        require_once $config['ftp_views']. '/' . $name . '.php';
        require_once $config['ftp_views']. '/footer.php';
    }

}