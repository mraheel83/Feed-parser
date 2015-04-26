<?php
/*
 * Helper Class with useful functions
 */

class Helper {

    public function getFormValue($key) {
        if(isset($_GET[$key]))
            $value = $_GET[$key];
        else if(isset($_POST[$key]))
            $value = $_POST[$key];
        else
            $value = null;

        return $value;
    }

    public function redirectTo($path, $key = '', $message = '') {
        if(isset($path)) {
            if(isset($key) && isset($message))
                $this->setSession($key, $message);

            Header( 'Location:' . $path);
            exit;
        }
    }

    public function printArray($arr) {
        echo "<pre>";
        print_r($arr);
    }

    public function setSession( $key, $value ) {
        if( isset($key) && isset($value))
            $_SESSION[$key] = $value;
    }

    public function deleteSession($key) {
        if( isset($key) && isset($_SESSION[$key]) )
            unset($_SESSION[$key]);
    }

    public function getSession( $key ) {
        if( isset($key)) {
            if(isset($_SESSION[$key]))
                return $_SESSION[$key];
        }
    }
}