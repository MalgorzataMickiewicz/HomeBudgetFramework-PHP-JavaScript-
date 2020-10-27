<?php

namespace App\Models;

use PDO;
use \Core\View;

/**
 *Expense model
 *
 * PHP version 7.0
 */


class Expense extends \Core\Model
{
    /**
    * Error messages
    *
    * @var array
    */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
     public function __construct($data = [])
     {
         foreach ($data as $key => $value) {
             $this->$key = $value;
         };
     }

    /**
     * Save the expense model with the current property values
     *
     * @return boolean  True if the expense was saved, false otherwise
     */

    public function saveExpense() 
    {
        $this->validate();
        if (empty($this->errors)) {

            $userId = $_SESSION['user_id'];
            $categoryExpense = $this->categoryExpense;
            $value = $this->valueExpense;

            //get value of choosen category id from database
            $categoryIdString = static::getCategoryId($categoryExpense);

            if ($categoryIdString) {
                $categoryIdIntiger = (int)$categoryIdString;
                
                //get value withouts comma and round to 2
                $valueDot = static::getValueWithDot($value);

                $sql = 'INSERT INTO expenses (userId, dateExpense, valueExpense, categoryExpenseId, commentExpense)
                VALUES (:userId, :dateExpense, :valueDot, :categoryIdIntiger, :commentExpense)';
                $db = static::getDB();
                $stmt = $db->prepare($sql);
        
                $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); 
                $stmt->bindValue(':dateExpense', $this->dateExpense, PDO::PARAM_STR); 
                $stmt->bindValue(':valueDot', $valueDot, PDO::PARAM_STR);
                $stmt->bindValue(':categoryIdIntiger', $categoryIdIntiger, PDO::PARAM_STR);
                $stmt->bindValue(':commentExpense', $this->commentExpense, PDO::PARAM_STR);
                return $stmt->execute();
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Value
        if ($this->valueExpense == '') {
            $this->errors[] = 'Kwota jest wymagana, wprowadź liczbę dodatnią, różną od 0';
        }

        // Date
        if (($this->dateExpense == '0000-00-00') || ($this->dateExpense == '')){
            $this->errors[] = 'Data jest wymagana';
        }

         // Category
         if (!isset($this->categoryExpense)) {
                $this->errors[] = 'Wybierz kategorię';
         }
        // Category
        if (!isset($this->payMethodExpense)) {
            $this->errors[] = 'Wybierz metodę płatności';
        }

    }   

    static function getCategoryId($categoryExpense) {

        $userId = $_SESSION['user_id'];

        $sql = 'SELECT id FROM expensescategoryassigned WHERE categoryName = :categoryExpense AND userId = :userId';
 
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':categoryExpense', $categoryExpense, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchColumn();

    }

    static function getValueWithDot($value) {
        $value = str_replace(",",".",$value); 
        $places = 2;
        $mult = pow(10, $places);
        $valueDot = ceil($value * $mult) / $mult;
        return $valueDot;
    }

    public static function findCategoriesByID($id) {
        $sql = 'SELECT * FROM expensescategoryassigned WHERE userId = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function saveCategoryExpense() {
        $userId = $_SESSION['user_id'];
        $newCategoryExpense = $this->newCategory;

        //check if new category already exist in database
        $data = static::checkCategoryName($newCategoryExpense);
        if (!$data) {

            $sql = 'INSERT INTO expensescategoryassigned (userId, categoryName)
            VALUES (:userId, :newCategoryExpense)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
        
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); 
            $stmt->bindValue(':newCategoryExpense', $newCategoryExpense, PDO::PARAM_STR); 
            return $stmt->execute();
        }
        else {
            return false;
        }
        
    }

    public function checkCategoryName($newCategoryExpense) {

        $userId = $_SESSION['user_id'];

        $sql = 'SELECT categoryName FROM expensescategoryassigned WHERE categoryName = :newCategoryExpense AND userId = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':categoryExpense', $newCategoryExpense, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetchColumn();
    }   

    static function findUserExpensesByIDCurrentMonth($id) {

        $curentDay = date('d');
        $curentMonth = date('m');
        $curentYear = date('Y');

        $dayOneThisMonth = $curentYear.'-'.$curentMonth.'-01';

        if($curentMonth == 1 || $curentMonth == 3 || $curentMonth == 5 || $curentMonth == 7 || $curentMonth == 8 || $curentMonth == 10 || $curentMonth == 12){
            $endDate = $curentYear.'-'.$curentMonth.'-31';
        }
        else if ($curentMonth == 4 || $curentMonth == 6 || $curentMonth == 9 || $curentMonth == 11){
            $endDate = $curentYear.'-'.$curentMonth.'-30';
        }
        else{
            if($curentYear % 4 == 0 && $curentYear % 100 != 0 || $curentYear % 400 == 0){
                $endDate = $curentYear.'-'.$curentMonth.'29';
            }
            else{
                $endDate = $curentYear.'-'.$curentMonth.'28';
            }
        }

        $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    static function findUserExpensesByIDPreviousMonth($id) {

        $curentDay = date('d');
        $curentMonth = date('m');
        $curentYear = date('Y');

        $dayOnePreviesMonth = $curentYear.'-'.$curentMonth.'-01';

        if($curentMonth == 1){
            $month = 12;
            $year = $curentYear - 1;
            $dayOneThisMonth = $year.'-'.$month.'-01';
        }
        else {
            $month = $curentMonth - 1;
            $year = $curentYear;
            $dayOneThisMonth = $year.'-'.$month.'-01';
        }

        if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12){
            $endDate = $year.'-'.$month.'-31';
        }
        else if ($month == 4 || $month == 6 || $month == 9 || $month == 11){
            $endDate = $year.'-'.$month.'-30';
        }
        else{
            if($year % 4 == 0 && $year % 100 != 0 || $year % 400 == 0){
                $endDate = $year.'-'.$month.'29';
            }
            else{
                $endDate = $year.'-'.$month.'28';
            }
        }

        $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    } 

    static function findUserExpensesByIDCurrentYear($id) {

        $curentDay = date('d');
            $curentMonth = date('m');
            $curentYear = date('Y');

            $dayOneThisMonth = $curentYear.'-01-01';
            $endDate = $curentYear.'-12-31';

        $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    } 

    static function findUserExpensesByIDNonstandard($id, $date) {

        $validate = static::validateDate($date);
        if ($validate == true) {
            $curentDay = date('d');
            $curentMonth = date('m');
            $curentYear = date('Y');

            $dayOneThisMonth = $date["dateFrom"];
            $endDate =  $date["dateTo"];

            $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            $stmt->execute();
            return $stmt->fetchAll();
        }
        else{
            return false;
        }
    } 

    static function validateDate($date) {
        // Date
        if ($date["dateFrom"] == '' || $date["dateTo"] == '') {
            return false;
        }
        else{
            return true;
        }
    }   
}