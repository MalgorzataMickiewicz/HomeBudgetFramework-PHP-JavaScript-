<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Flash;

/**
 * Bilans controller
 *
 * PHP version 7.0
 */
class Bilans extends Authenticated

{
    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */

     //Show bilans for current month
    public function currentMonthAction() {    
        $this->userIncomes = Income::findUserIncomesByIDCurrentMonth();
        $this->userExpenses = Expense::findUserExpensesByIDCurrentMonth();
        View::renderTemplate('Bilans/currentMonth.html', [
            'userIncomes' => $this->userIncomes,
            'userExpenses' => $this->userExpenses
        ]);
    }

     //Show bilans for previous month
    public function previousMonthAction() {    
        $this->userIncomes = Income::findUserIncomesByIDPreviousMonth();
        $this->userExpenses = Expense::findUserExpensesByIDPreviousMonth();
            View::renderTemplate('Bilans/previousMonth.html', [
                'userIncomes' => $this->userIncomes,
                'userExpenses' => $this->userExpenses
            ]);
    }

    //Show bilans for current year
    public function currentYearAction() {    
        $this->userIncomes = Income::findUserIncomesByIDCurrentYear();
        $this->userExpenses = Expense::findUserExpensesByIDCurrentYear();
            View::renderTemplate('Bilans/currentYear.html', [
                'userIncomes' => $this->userIncomes,
                'userExpenses' => $this->userExpenses
            ]);
    }
    
    //Show template for nonstandard
    public function nonstandardAction() { 
        View::renderTemplate('Bilans/nonstandard.html');
    }

    //Generate bilans for nonstandard Date
    public function nonstandardDateAction() { 
        $date = $_POST;

        $this->userIncomes = Income::findUserIncomesByIDNonstandard($date);
        $this->userExpenses = Expense::findUserExpensesByIDNonstandard($date);

        $arr = Array();

        $dateFrom = $_POST['dateFrom'];
        $dateTo = $_POST['dateTo'];

        $obj0 = (object) array();
        $obj0->dateFrom = $dateFrom;
        $obj0->dateTo = $dateTo;

        $arr[0] = $obj0;

        View::renderTemplate('Bilans/nonstandard.html', [
            'userIncomes' => $this->userIncomes,
            'userExpenses' => $this->userExpenses,
            'obj' => $arr
        ]);
    }

    //Edit Income
    public function editIncome() {
        $array = $_POST;
        $edit = Income::editIncome($array);
    }

    //Edit Expense
    public function editExpense() {
        $array = $_POST;
        $edit = Expense::editExpense($array);
    }

    //Delete Income
    public function deleteIncome() {
        $array = $_POST;
        $delete = Income::deleteIncome($array);
    }

    //Delete Expense
    public function deleteExpense() {
        $array = $_POST;
        $delete = Expense::deleteExpense($array);
    }

    public function checkCategoryLimit() {
        $categoryArray = $_POST;
        $limitCategoryExpense = Expense::checkCategoryLimit($categoryArray);
        echo $limitCategoryExpense;
    }

    public function checkCategoryLimitEdit() {
        $categoryArray = $_POST;
        $limitCategoryExpense = Expense::checkCategoryLimitEdit($categoryArray);
        echo $limitCategoryExpense;
    }
}