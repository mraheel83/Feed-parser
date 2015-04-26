<?php

/*
 * Core Controller Class
 */

class Controller extends Helper {
    protected $view;

    public function __constructor() {
        $this->view = new View();
    }

}