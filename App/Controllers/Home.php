<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends Authenticated

{
    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    /*

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }
}
