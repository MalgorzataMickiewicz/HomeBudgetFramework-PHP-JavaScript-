<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Income;
use \App\Models\Expense;

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
    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    /**
     * Show the profile
     *
     * @return void
     */
    public function showAction()
    {
        View::renderTemplate('Profile/show.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editAction()
    {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Show the form for editing the cateogires
     *
     * @return void
     */
    public function categoriesAction()
    {
        $this->categoryIncome = Auth::getUserIncome();
        $this->categoryExpense = Auth::getUserExpense();

        View::renderTemplate('Profile/categories.html', [
            'income' => $this->categoryIncome,
            'expense' => $this->categoryExpense
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
            Flash::addMessage('Nie udało się dodać kategorii', Flash::WARNING);
            View::renderTemplate('Profile/categories.html');
        }
    }

    public function saveCategoryExpense() {
        $categoryExpense = new Expense($_POST);
        if($categoryExpense -> saveCategoryExpense()) {
            Flash::addMessage('Kategorię dodano pomyślnie');
            $this->redirect('/Profile/categories');
            } 
        else{
            Flash::addMessage('Nie udało się dodać kategorii', Flash::WARNING);
            View::renderTemplate('Profile/categories.html');
        }
    }
}
