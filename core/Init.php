<?php

/*
 * Initializing Class
 */

class Init {

    // constructor
    public function __construct() {

        // Global config array
        global $config;

        $path = isset($_GET['path']) ? $_GET['path'] : null;
        $path = rtrim($path, '/');
        $parts = explode('/', $path);

        if( empty($parts[0]) ) {          // URL parts empty

            require_once $config['ftp_controllers'] . '/index.php';
            $index = new Index();
            $index->index();
            return false;

        } else {                        // URL parts not empty

            $file = $config['ftp_controllers'] . '/' . $parts[0] . '.php';

            if( file_exists($file) ) {

                require_once $file;         // require file
                $controller = new $parts[0];  // initialize class

                if( isset($parts[2]) ) {
                    if( method_exists($controller, $parts[1]) ) {
                        $controller->{$parts[1]}($parts[2]);    // Initiate class with action function
                    } else {
                        $this->log_error("Class method does not exists\n");
                    }
                } else {
                    if(isset($parts[1])){
                        if( method_exists($controller, $parts[1]) ){
                            $controller->{$parts[1]}();         // Initiate class
                        } else {
                            $this->log_error("Class method does not exist\n");
                        }
                    } else {
                        // Render to view
                        $controller->index();
                    }
                }
            } else {
                $this->log_error("Class file does not exist\n");
            }
        }
    }

    private function log_error($error) {
        global $config;
        require_once $config['ftp_core'] . '/Error.php';
        $objError = new Error($config['log_file']);
        $objError->log_error($error);
        return false;
    }

}