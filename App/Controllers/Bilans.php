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
class Bilans extends \Core\Controller
{
    /**
     * Show bilans for current month
     *
     * @return void
     */
    public function currentMonthAction() {    
        if ($this->userIncomes = Auth::getUserIncomesBilansCurrentMonth()) {
            if ($this->userExpenses = Auth::getUserExpensesBilansCurrentMonth()) {
                View::renderTemplate('Bilans/currentMonth.html', [
                    'userIncomes' => $this->userIncomes,
                    'userExpenses' => $this->userExpenses
                ]);
            }
            else {
                View::renderTemplate('Bilans/currentMonth.html', [
                    'userIncomes' => $this->userIncomes
                    ]);
            }
        }
        else {
            if ($this->userExpenses = Auth::getUserExpensesBilansCurrentMonth()) {
                if ($this->userIncomes = Auth::getUserIncomesBilansCurrentMonth()) {
                    View::renderTemplate('Bilans/currentMonth.html', [
                        'userIncomes' => $this->userIncomes,
                        'userExpenses' => $this->userExpenses
                    ]);
                }
                else {
                    View::renderTemplate('Bilans/currentMonth.html', [
                        'userExpenses' => $this->userExpenses
                        ]);
                }
            }
            else {
                View::renderTemplate('Bilans/currentMonth.html');
            }
        }
    }

    /**
     * Show bilans for previous month
     *
     * @return void
     */
    public function previousMonthAction() {    
        if ($this->userIncomes = Auth::getUserIncomesBilansPreviousMonth()) {
            if ($this->userExpenses = Auth::getUserExpensesBilansPreviousMonth()) {
                View::renderTemplate('Bilans/previousMonth.html', [
                    'userIncomes' => $this->userIncomes,
                    'userExpenses' => $this->userExpenses
                ]);
            }
            else {
                View::renderTemplate('Bilans/previousMonth.html', [
                    'userIncomes' => $this->userIncomes
                    ]);
            }
        }
        else {
            if ($this->userExpenses = Auth::getUserExpensesBilansPreviousMonth()) {
                if ($this->userIncomes = Auth::getUserIncomesBilansPreviousMonth()) {
                    View::renderTemplate('Bilans/previousMonth.html', [
                        'userIncomes' => $this->userIncomes,
                        'userExpenses' => $this->userExpenses
                    ]);
                }
                else {
                    View::renderTemplate('Bilans/previousMonth.html', [
                        'userExpenses' => $this->userExpenses
                        ]);
                }
            }
            else {
                View::renderTemplate('Bilans/previousMonth.html');
            }
        }
    }

    /**
     * Show bilans for current year
     *
     * @return void
     */
    public function currentYearAction() {    
        if ($this->userIncomes = Auth::getUserIncomesBilansCurrentYear()) {
            if ($this->userExpenses = Auth::getUserExpensesBilansCurrentYear()) {
                View::renderTemplate('Bilans/currentYear.html', [
                    'userIncomes' => $this->userIncomes,
                    'userExpenses' => $this->userExpenses
                ]);
            }
            else {
                View::renderTemplate('Bilans/currentYear.html', [
                    'userIncomes' => $this->userIncomes
                    ]);
            }
        }
        else {
            if ($this->userExpenses = Auth::getUserExpensesBilansCurrentYear()) {
                if ($this->userIncomes = Auth::getUserIncomesBilansCurrentYear()) {
                    View::renderTemplate('Bilans/currentYear.html', [
                        'userIncomes' => $this->userIncomes,
                        'userExpenses' => $this->userExpenses
                    ]);
                }
                else {
                    View::renderTemplate('Bilans/currentMonth.html', [
                        'userExpenses' => $this->userExpenses
                        ]);
                }
            }
            else {
                View::renderTemplate('Bilans/currentMonth.html');
            }
        }
    }

      /**
     * Show bilans for nonstandard
     *
     * @return void
     */
    public function nonstandardAction() { 
        View::renderTemplate('Bilans/nonstandard.html');
    }

    public function nonstandardDateAction() { 
        $date = $_POST;

        if ($this->userIncomes = Auth::getUserIncomesBilansNonstandard($date)) {
            if ($this->userExpenses = Auth::getUserExpensesBilansNonstandard($date)) {
                View::renderTemplate('Bilans/nonstandard.html', [
                    'userIncomes' => $this->userIncomes,
                    'userExpenses' => $this->userExpenses
                ]);
            }
            else {
                View::renderTemplate('Bilans/nonstandard.html', [
                    'userIncomes' => $this->userIncomes
                    ]);
            }
        }
        else {
            if ($this->userExpenses = Auth::getUserExpensesBilansNonstandard($date)) {
                if ($this->userIncomes = Auth::getUserIncomesBilansNonstandard($date)) {
                    View::renderTemplate('Bilans/nonstandard.html', [
                        'userIncomes' => $this->userIncomes,
                        'userExpenses' => $this->userExpenses
                    ]);
                }
                else {
                    View::renderTemplate('Bilans/nontandard.html', [
                        'userExpenses' => $this->userExpenses
                        ]);
                }
            }
            else {
                View::renderTemplate('Bilans/nonstandard.html');
            }
        }
    } 
}