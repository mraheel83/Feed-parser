<?php

/*
 * Error logging Class
 */

class Error {
    public function __construct($log_file) {
        $this->log_file = $log_file;
    }
    public function log_error( $error ) {
        error_log( $error , 3 , $this->log_file );
    }
}