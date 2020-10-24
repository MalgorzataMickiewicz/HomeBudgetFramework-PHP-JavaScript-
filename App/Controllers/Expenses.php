<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Auth;
use \App\Flash;

/**
 * Expenses controller (example)
 *
 * PHP version 7.0
 */

class Expenses extends Authenticated
{

    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    /*

    /**
     * Expenses index
     *
     * @return void
     */
    public function indexAction() {
        $this->categoryExpense = Auth::getUserExpense();

        View::renderTemplate('Expenses/index.html', [
            'categoryExpense' => $this->categoryExpense
        ]);
    }

      /**
     * Add a new expense
     *
     * @return void
     */
    
    public function saveExpense()
    {
        $expense = new Expense($_POST);

        if($expense -> saveExpense()) {
            Flash::addMessage('Wydatek dodano pomyślnie');
            $this->redirect('/Expenses/index');
            } 
        else{
            Flash::addMessage('Nie udało się dodać wydatku', Flash::WARNING);
            View::renderTemplate('Expenses/index.html', [
            'expense' => $expense
            ]);
        }
    }
}
