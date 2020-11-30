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

    static function saveExpense($expense) {
        //$validation = static::validation($expense);
        //if (empty($validation)) {
        
            $userId = $_SESSION['user_id'];
            $categoryExpense = $expense['categoryExpense'];
            $methodPay = $expense['payMethodExpense'];
            $value = $expense['valueExpense'];
            $dateExpense = $expense['dateExpense'];
            $commentExpense = $expense['commentExpense'];
            
                //convert string to int
                $categoryIdIntiger = (int)$categoryExpense;
                $methodPayIdIntiger = (int)$methodPay;

                //get value withouts comma and round to 2
                $valueDot = static::getValueWithDot($value);

                $valueString = (string)$valueDot;

                $sql = 'INSERT INTO expenses (userId, dateExpense, valueExpense, categoryExpenseId, commentExpense, idMethodPay) VALUES (:userId, :dateExpense, :valueString, :categoryIdIntiger, :commentExpense, :methodPayIdIntiger)';

                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); 
                $stmt->bindValue(':dateExpense', $dateExpense, PDO::PARAM_STR); 
                $stmt->bindValue(':valueString', $valueString, PDO::PARAM_STR);
                $stmt->bindValue(':categoryIdIntiger', $categoryIdIntiger, PDO::PARAM_INT);
                $stmt->bindValue(':commentExpense', $commentExpense, PDO::PARAM_STR);
                $stmt->bindValue(':methodPayIdIntiger', $methodPayIdIntiger, PDO::PARAM_INT);

                $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
                return $stmt->execute();
      //  }
        //else {
          //  return false;
        //}
    }

    static function validation($categoryArray) {
        $validation = [];
        $value = $categoryArray['value'];
        $dateExpense = $categoryArray['date'];

        // Value
        if(($value == '')||($value == '0')) {
            array_push($validation, 'Kwota jest wymagana, wprowadź liczbę dodatnią, różną od 0'); 
        }

        // Date
        if(($dateExpense == '0000-00-00')||($dateExpense == '')) {
            array_push($validation, 'Data jest wymagana'); 
        }

        return $validation;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Value
        if (($this->valueExpense == '')||($this->valueExpense == '0')) {
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
        // MethodPay
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
        var_dump($stmt->fetchColumn());
        return $stmt->fetchColumn();
    }

    static function getMethodPayId($methodPay) {

        $userId = $_SESSION['user_id'];

        $sql = 'SELECT id FROM methodpayassigned WHERE nameMethodPay = :methodPay AND userId = :userId';
 
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':methodPay', $methodPay, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);

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

    public static function findCategoriesByID() {

        $id = $_SESSION['user_id'];

        $sql = 'SELECT * FROM expensescategoryassigned WHERE userId = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findMethodPayByID() {
        $id = $_SESSION['user_id'];

        $sql = 'SELECT * FROM methodpayassigned WHERE userId = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function saveCategoryExpense() {
        $userId = $_SESSION['user_id'];
        $newCategory = $this->newCategory;
        $expenseLimit = 0;

        //check if new category already exist in database
        $data = static::checkCategoryName($newCategory);
        if (!$data) {

            $newCategoryExpense = ucwords($newCategory);
            $sql = 'INSERT INTO expensescategoryassigned (userId, categoryName, expenseLimit)
            VALUES (:userId, :newCategoryExpense, :expenseLimit)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
        
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); 
            $stmt->bindValue(':newCategoryExpense', $newCategoryExpense, PDO::PARAM_STR); 
            $stmt->bindValue(':expenseLimit', $expenseLimit, PDO::PARAM_INT); 
            return $stmt->execute();
        }
        else {
            return false;
        }
    }

    static function checkCategoryName($newCategory) {

        $userId = $_SESSION['user_id'];
        $newCategoryExpense = ucwords($newCategory);

        $sql = 'SELECT categoryName FROM expensescategoryassigned WHERE categoryName = :newCategoryExpense AND userId = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':newCategoryExpense', $newCategoryExpense, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }   

    static function findUserExpensesByIDCurrentMonth() {

        $id = $_SESSION['user_id'];

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

        $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate order by expenses.dateExpense';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    static function findUserExpensesByIDPreviousMonth() {

        $id = $_SESSION['user_id'];

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

        $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate order by expenses.dateExpense';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    } 

    static function findUserExpensesByIDCurrentYear() {

        $id = $_SESSION['user_id'];

        $curentDay = date('d');
            $curentMonth = date('m');
            $curentYear = date('Y');

            $dayOneThisMonth = $curentYear.'-01-01';
            $endDate = $curentYear.'-12-31';

        $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate order by expenses.dateExpense';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    } 

    static function findUserExpensesByIDNonstandard($date) {

        $id = $_SESSION['user_id'];

        $validate = static::validateDate($date);
        if ($validate == true) {
            $curentDay = date('d');
            $curentMonth = date('m');
            $curentYear = date('Y');

            $dayOneThisMonth = $date["dateFrom"];
            $endDate =  $date["dateTo"];

            $sql = 'SELECT * FROM expenses INNER JOIN expensescategoryassigned ON expenses.categoryExpenseId = expensescategoryassigned.id AND expenses.userId = :id AND expenses.dateExpense >= :dayOneThisMonth AND expenses.dateExpense <= :endDate order by expenses.dateExpense';

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

    static function updateCategoryExpense($categoryArray) {
        $userId = $_SESSION['user_id'];

        $newCategory = $categoryArray['newCategory'];
        $categoryId = $categoryArray['newCategoryId'];

        // check existing categories in base
        $data = static::checkCategoryName($newCategory);

        if (!$data) {
            // update category in assigned categories
            $newCategoryExpense = ucwords($newCategory);
            $sql = 'UPDATE expensescategoryassigned SET categoryName = :newCategory WHERE userId = :userId AND id = :categoryId';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT); 
            $stmt->bindValue(':newCategory', $newCategory, PDO::PARAM_STR); 

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            
            return $stmt->execute();
        }
        return false;
    }

    static function deleteExpenseForSpecificCategory($categoryArray) {
        $userId = $_SESSION['user_id'];

        $categoryId = $categoryArray['categoryId'];

        $sql = 'DELETE FROM expenses WHERE userId = :userId AND categoryIncomeId = :categoryId';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }

    static function deleteExpenseForSpecificMethodPay($categoryArray) {
        $userId = $_SESSION['user_id'];

        $categoryId = $categoryArray['categoryId'];

        $sql = 'DELETE FROM expenses WHERE userId = :userId AND idMethodPay = :categoryId';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }

    static function checkValueExpenses($categoryId, $date){
        $userId = $_SESSION['user_id'];

        $dateYear = substr($date, -10, 4);
        $dateMonth = substr($date, -5, 2);
        $dateFrom = $dateYear.'-'.$dateMonth.'-01';
        $dateTo = $dateYear.'-'.$dateMonth.'-31';

        $sql = 'SELECT valueExpense FROM expenses WHERE userId = :userId AND categoryExpenseId = :categoryId AND dateExpense >= :dateFrom AND dateExpense <= :dateTo';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
        $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function checkValueExpensesEdit($categoryId, $date, $id){
        $userId = $_SESSION['user_id'];

        $dateYear = substr($date, -10, 4);
        $dateMonth = substr($date, -5, 2);
        $dateFrom = $dateYear.'-'.$dateMonth.'-01';
        $dateTo = $dateYear.'-'.$dateMonth.'-31';

        $sql = 'SELECT valueExpense FROM expenses WHERE userId = :userId AND categoryExpenseId = :categoryId AND dateExpense >= :dateFrom AND dateExpense <= :dateTo AND idExpense <> :id';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
        $stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR); 
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetchAll();
    }

    static function checkCategoryLimit($categoryArray) {
        $userId = $_SESSION['user_id'];

        $categoryId = $categoryArray['categoryId'];
        $value = $categoryArray['value'];
        $date = $categoryArray['date'];

        $validation = static::validation($categoryArray);

        if (empty($validation)) {

            // get limit for categoryId
            $sql = 'SELECT expenseLimit FROM expensescategoryassigned WHERE userId = :userId AND id = :categoryId';
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT); 

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            $limit = $stmt->fetchColumn();
            $result = '';
            if($limit == 0) {
                $result = 'nolimit';
                return $result;
            }
            else {
                $tab = static::checkValueExpenses($categoryId, $date);
                $numberOfItem = count($tab);
                $sumValue = 0;
                for($i = 0; $i < $numberOfItem; $i++){
                    $sumValue = $sumValue + $tab[$i]->valueExpense;
                }
                
                if ($sumValue + $value <= $limit) {
                    $result = $limit - ($sumValue + $value);
                    return $result;
                }
                else {
                    $result = $limit - ($sumValue + $value);
                    return $result;
                }
            }
        }
        else {
            $result = 'false';
            return $result;
        }
    }

    static function checkCategoryLimitEdit($categoryArray) {
        $userId = $_SESSION['user_id'];

        $categoryId = $categoryArray['categoryId'];
        $value = $categoryArray['value'];
        $date = $categoryArray['date'];
        $idExpense = $categoryArray['idExpense'];

        $validation = static::validation($categoryArray);

        if (empty($validation)) {

            // get limit for categoryId
            $sql = 'SELECT expenseLimit FROM expensescategoryassigned WHERE userId = :userId AND id = :categoryId';
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT); 

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            $limit = $stmt->fetchColumn();
            $result = '';
            if($limit == 0) {
                $result = 'nolimit';
                return $result;
            }
            else {
                $tab = static::checkValueExpensesEdit($categoryId, $date, $idExpense);
                $numberOfItem = count($tab);
                $sumValue = 0;
                for($i = 0; $i < $numberOfItem; $i++){
                    $sumValue = $sumValue + $tab[$i]->valueExpense;
                }
                
                if ($sumValue + $value <= $limit) {
                    $result = $limit - ($sumValue + $value);
                    return $result;
                }
                else {
                    $result = $limit - ($sumValue + $value);
                    return $result;
                }
            }
        }
        else {
            $result = 'false';
            return $result;
        }
    }

    static function editExpense($array) {
        $userId = $_SESSION['user_id'];

        $idExpense = $array['idExpense'];
        $newValue = $array['newValue'];

        $sql = 'UPDATE expenses SET valueExpense = :newValue WHERE userId = :userId AND idExpense = :idExpense';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':idExpense', $idExpense, PDO::PARAM_INT); 
        $stmt->bindValue(':newValue', $newValue, PDO::PARAM_STR); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }

    static function deleteExpense($array) {
        
        $userId = $_SESSION['user_id'];
        $idExpense = $array['idExpense'];

        $sql = 'DELETE FROM expenses WHERE userId = :userId AND idExpense = :idExpense';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':idExpense', $idExpense, PDO::PARAM_INT); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }
}