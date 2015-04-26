<?php
/*
 * Index Controller
 */

class Index extends Controller {

    public $parser;

    public function __construct() {

        parent::__constructor();
        $this->parser = new Parser();

    }

    public function index() {

        $this->view->render('index/index');

    }

    public function parse() {
        global $config;
        $feedUrl = $this->getFormValue('feedUrl');

        if($feedUrl) {
            $this->parser->parse($feedUrl);
        } else {
            $this->redirectTo($config['redirect_home'], 'error', 'Invalid Feed URL' );
        }
    }
}