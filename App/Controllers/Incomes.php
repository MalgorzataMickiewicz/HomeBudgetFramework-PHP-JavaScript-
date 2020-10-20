<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Auth;

/**
 * Incomes controller (example)
 *
 * PHP version 7.0
 */

class Incomes extends Authenticated
{

    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    /*

    /**
     * Incomes index
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Incomes/index.html');
    }

      /**
     * Add a new income
     *
     * @return void
     */
    public function saveIncome()
    {
        $income = new Income($_POST);

          if($income -> saveIncome()) {
            echo "Dodano przychód";
         } 
        else{
            echo "Nie dodano przychodu";
        }
        
    }
}
