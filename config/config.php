<?php
session_start();

// Application configuration
$config = array();

// Http path
$config['http_base']           = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$config['http_public']         = $config['http_base']. 'public/';
$config['http_css']            = $config['http_public']. 'css/';
$config['http_js']             = $config['http_public']. 'js/';
$config['http_lib']            = $config['http_public']. 'lib/';
$config['redirect_home']       = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// File path
$config['ftp_path']            = BASE_PATH;
$config['ftp_app']             = $config['ftp_path'].'/app';
$config['ftp_core']            = $config['ftp_path'].'/core';
$config['ftp_log']             = $config['ftp_path'].'/log';
$config['ftp_data']            = $config['ftp_path'].'/data';
$config['log_file']            = $config['ftp_log'].'/error.log';

// Controller & View path
$config['ftp_controllers']     = $config['ftp_app'].'/controllers';
$config['ftp_views']           = $config['ftp_app'].'/views';

// Application title
$config['page_title']          = 'TradeTracker - Processing Product Feeds';

// Development environment
$config['mode']                = 'development';

// Data file prefix
$config['prefix_datafile']     = 'datafile-';


// PHP Error array notation for better understanding into error message
$php_error = array();
$php_error[1] = 'E_ERROR';
$php_error[2] = 'E_WARNING';
$php_error[4] = 'E_PARSE';
$php_error[8] = 'E_NOTICE';
$php_error[16] = 'E_CORE_ERROR';
$php_error[32] = 'E_CORE_WARNING';
$php_error[64] = 'E_COMPILE_ERROR';
$php_error[128] = 'E_COMPILE_WARNING';
$php_error[256] = 'E_USER_ERROR';
$php_error[512] = 'E_USER_WARNING';
$php_error[1024] = 'E_USER_NOTICE';
$php_error[2048] = 'E_STRICT';
$php_error[4096] = 'E_RECOVERABLE_ERROR';
$php_error[8192] = 'E_DEPRECATED';
$php_error[16384] = 'E_USER_DEPRECATED';
$php_error[32767] = 'E_ALL';

if($config['mode'] == 'development') {

    ini_set('memory_limit','32M');
    error_reporting(E_ALL | E_STRICT);
    ini_set("display_errors", 1);

    // Define custom error handler function
    function customErrorHandler() {
        global $config, $php_error;
        $helper = new Helper();
        $objError = new Error( $config['log_file'] );

        // Last error detected
        $error = error_get_last();
        //$helper->printArray($error);

        if( is_array($error) ) {

            $message = $php_error[$error['type']] . ' : ' . $error['message'];

            // Products created
            $products = $helper->getSession('products');

            if(isset($products))
                $message .= ' - ( '.$products .' ) products has created.';

            $message .= "\n";

            $objError->log_error($message);
            $helper->redirectTo($config['redirect_home'], 'error', $message );
        }
    }

    register_shutdown_function('customErrorHandler');

}