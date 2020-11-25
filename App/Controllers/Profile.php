<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\User;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Profile extends Authenticated
{

    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before() {
        parent::before();

        $this->user = Auth::getUser();
    }

    /**
     * Show the profile
     *
     * @return void
     */
    public function showAction() {
        View::renderTemplate('Profile/show.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editAction() {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Show the form for editing the cateogires
     *
     * @return void
     */
    public function categoriesAction() {
        $this->categoryIncome = Income::findCategoriesByID();
        $this->categoryExpense = Expense::findCategoriesByID();
        $this->methodPay = Expense::findMethodPayByID();

        View::renderTemplate('Profile/categories.html', [
            'income' => $this->categoryIncome,
            'expense' => $this->categoryExpense,
            'methodPay' => $this->methodPay
        ]);
    }

    /**
     * Update the profile
     *
     * @return void
     */
    public function updateAction() {
        if ($this->user->updateProfile($_POST)) {
            Flash::addMessage('Zmiany zapisano');
            $this->redirect('/profile/edit');

        } else 
            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);
        }

    public function saveCategoryIncome() {
        $categoryIncome = new Income($_POST);
        if($categoryIncome -> saveCategoryIncome()) {
            Flash::addMessage('Kategorię dodano pomyślnie');
            $this->redirect('/Profile/categories');
            } 
        else{
            Flash::addMessage('Nie udało się dodać kategorii, sprawdź czy nie posiadasz już takiej', Flash::WARNING);
            $this->redirect('/Profile/categories');
        }
    }

    public function saveCategoryExpense() {
        $categoryExpense = new Expense($_POST);
        if($categoryExpense -> saveCategoryExpense()) {
            Flash::addMessage('Kategorię dodano pomyślnie');
            $this->redirect('/Profile/categories');
            } 
        else{
            Flash::addMessage('Nie udało się dodać kategorii, sprawdź czy nie posiadasz już takiej', Flash::WARNING);
            $this->redirect('/Profile/categories');
        }
    }

    public function saveMethodPay() {
        $methodPay = new User($_POST);
        if($methodPay -> saveMethodPay()) {
            Flash::addMessage('Metodę płatności dodano pomyślnie');
            $this->redirect('/Profile/categories');
            } 
        else{
            Flash::addMessage('Nie udało się dodać metody płatności, sprawdź czy nie posiadasz już takiej', Flash::WARNING);
            $this->redirect('/Profile/categories');
        }
    }

    public function updateNewCategoryIncome() {
        $categoryArray = $_POST;
        $updatedCategory = Income::updateCategoryIncome($categoryArray);
    }

    public function updateNewCategoryExpense() {
        $categoryArray = $_POST;
        $updatedCategory = Expense::updateCategoryExpense($categoryArray);
    }

    public function updateNewMethodPay() {
        $categoryArray = $_POST;
        $updatedCategory = User::updateMethodPay($categoryArray);
    }

    public function deleteCategoryIncome() {
        $categoryArray = $_POST;
        $deleteCategory = User::deleteCategoryIncome($categoryArray);
        $deleteIncome = Income::deleteIncomeForSpecificCategory($categoryArray);
    }

    public function deleteCategoryExpense() {
        $categoryArray = $_POST;
        $deleteCategory = User::deleteCategoryExpense($categoryArray);
        $deleteExpense = Expense::deleteExpenseForSpecificCategory($categoryArray);
    }

    public function deleteMethodPay() {
        $categoryArray = $_POST;
        $deleteMethodPay = User::deleteMethodPay($categoryArray);
        $deleteExpense = Expense::deleteExpenseForSpecificMethodPay($categoryArray);
    }
    
    public function setExpenseLimit() {
        $categoryArray = $_POST;
        $limitCategoryExpense = User::setExpenseLimit($categoryArray);
    }
}