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
        $this->categoryExpense = Expense::findCategoriesByID();
        $this->methodPay = Expense::findMethodPayByID();

        View::renderTemplate('Expenses/index.html', [
            'categoryExpense' => $this->categoryExpense,
            'methodPay' => $this->methodPay
        ]);
    }

      /**
     * Add a new expense
     *
     * @return void
     */
    
    public function saveExpense() {
        $expense = $_POST;
        $result = Expense::saveExpense($expense);
        if($result == true) {
            echo $result;
        }
        else {
            echo '2';
        }
        
    }

    public function checkCategoryLimit() {
        $categoryArray = $_POST;
        $limitCategoryExpense = Expense::checkCategoryLimit($categoryArray);
        echo $limitCategoryExpense;
    }
}
