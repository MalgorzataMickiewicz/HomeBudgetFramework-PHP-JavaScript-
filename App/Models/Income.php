<?php

namespace App\Models;

use PDO;
use \Core\View;

/**
 *Income model
 *
 * PHP version 7.0
 */


class Income extends \Core\Model
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
     * Save the income model with the current property values
     *
     * @return boolean  True if the income was saved, false otherwise
     */

    public function saveIncome() 
    {
        $this->validate();
        if (empty($this->errors)) {

            $userId = $_SESSION['user_id'];
            $categoryIncome = $this->categoryIncome;
            $valueIncome = $this->valueIncome;
            var_dump($valueIncome);
            $valueDot = static::getValueWithDot($valueIncome);

            //get value of choosen category id from database
            $categoryIdString = static::getCategoryId($categoryIncome);
            if ($categoryIdString) {
                $categoryIdIntiger = (int)$categoryIdString;

                $sql = 'INSERT INTO incomes (userId, dateIncome, valueIncome, categoryIncomeId, commentIncome)
                VALUES (:userId, :dateIncome, :valueDot, :categoryIdIntiger, :commentIncome)';
                $db = static::getDB();
                $stmt = $db->prepare($sql);
        
                $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); 
                $stmt->bindValue(':dateIncome', $this->dateIncome, PDO::PARAM_STR); 
                $stmt->bindValue(':valueDot', $valueDot, PDO::PARAM_STR);
                $stmt->bindValue(':categoryIdIntiger', $categoryIdIntiger, PDO::PARAM_STR);
                $stmt->bindValue(':commentIncome', $this->commentIncome, PDO::PARAM_STR);
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
        if ($this->valueIncome == '') {
            $this->errors[] = 'Kwota jest wymagana, wprowadź liczbę dodatnią, różną od 0';
        }

        // Date
        if (($this->dateIncome == '0000-00-00') || ($this->dateIncome == '')){
            $this->errors[] = 'Data jest wymagana';
        }

         // Category
         if (!isset($this->categoryIncome)) {
                $this->errors[] = 'Wybierz kategorię';
         }
    }   

    static function getCategoryId($categoryIncome) {

        $userId = $_SESSION['user_id'];

        $sql = 'SELECT id FROM incomescategoryassigned WHERE categoryName = :categoryIncome AND userId = :userId';
 
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':categoryIncome', $categoryIncome, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchColumn();

    }

    static function getValueWithDot($value) {
        $value = str_replace(",",".",$value); 
        $places = 2;
        $mult = pow(10, $places);
        $english_format_number = number_format($value, 2, '.', '');
        return $english_format_number;
    }

    public static function findCategoriesByID() {
        $id = $_SESSION['user_id'];

        $sql = 'SELECT * FROM incomescategoryassigned WHERE userId = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Save the income model with the current property values
     *
     * @return boolean  True if the income was saved, false otherwise
     */

    public function saveCategoryIncome() {
            $userId = $_SESSION['user_id'];
            $newCategory = $this->newCategory;

            //check if new category already exist in database
            $data = static::checkCategoryName($newCategory);
            if (!$data) {
                
                $newCategoryIncome = ucwords($newCategory);
                $sql = 'INSERT INTO incomescategoryassigned (userId, categoryName)
                VALUES (:userId, :newCategoryIncome)';
                $db = static::getDB();
                $stmt = $db->prepare($sql);
        
                $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); 
                $stmt->bindValue(':newCategoryIncome', $newCategoryIncome, PDO::PARAM_STR); 
                return $stmt->execute();
            }
            else {
                return false;
            }
    }

    static function checkCategoryName($newCategory) {

        $userId = $_SESSION['user_id'];
        $newCategoryIncome = ucwords($newCategory);

        $sql = 'SELECT categoryName FROM incomescategoryassigned WHERE categoryName = :newCategoryIncome AND userId = :userId';
  
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':newCategoryIncome', $newCategoryIncome, PDO::PARAM_STR);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        return $stmt->fetchColumn();
    }   

    static function findUserIncomesByIDCurrentMonth() {

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

        $sql = 'SELECT * FROM incomes INNER JOIN incomescategoryassigned ON incomes.categoryIncomeId = incomescategoryassigned.id AND incomes.userId = :id AND incomes.dateIncome >= :dayOneThisMonth AND incomes.dateIncome <= :endDate order by incomes.dateIncome';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    }

    static function findUserIncomesByIDPreviousMonth() {

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

        $sql = 'SELECT * FROM incomes INNER JOIN incomescategoryassigned ON incomes.categoryIncomeId = incomescategoryassigned.id AND incomes.userId = :id AND incomes.dateIncome >= :dayOneThisMonth AND incomes.dateIncome <= :endDate order by incomes.dateIncome';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    } 

    static function findUserIncomesByIDCurrentYear() {

        $id = $_SESSION['user_id'];

        $curentDay = date('d');
            $curentMonth = date('m');
            $curentYear = date('Y');

            $dayOneThisMonth = $curentYear.'-01-01';
            $endDate = $curentYear.'-12-31';

        $sql = 'SELECT * FROM incomes INNER JOIN incomescategoryassigned ON incomes.categoryIncomeId = incomescategoryassigned.id AND incomes.userId = :id AND incomes.dateIncome >= :dayOneThisMonth AND incomes.dateIncome <= :endDate order by incomes.dateIncome';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dayOneThisMonth', $dayOneThisMonth, PDO::PARAM_STR);
        $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        return $stmt->fetchAll();
    } 

    static function findUserIncomesByIDNonstandard($date) {

        $id = $_SESSION['user_id'];

        $validate = static::validateDate($date);
        if ($validate == true) {
            $curentDay = date('d');
            $curentMonth = date('m');
            $curentYear = date('Y');

            $dayOneThisMonth = $date['dateFrom'];
            $endDate =  $date['dateTo'];

            $sql = 'SELECT * FROM incomes INNER JOIN incomescategoryassigned ON incomes.categoryIncomeId = incomescategoryassigned.id AND incomes.userId = :id AND incomes.dateIncome >= :dayOneThisMonth AND incomes.dateIncome <= :endDate order by incomes.dateIncome';

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

    static function updateCategoryIncome($categoryArray) {
        $userId = $_SESSION['user_id'];

        $newCategory = $categoryArray['newCategory'];
        $categoryId = $categoryArray['newCategoryId'];

        // check existing categories in base
        $data = static::checkCategoryName($newCategory);
        if (!$data) {
            // update category in assigned categories
            $newCategoryIncome = ucwords($newCategory);
            $sql = 'UPDATE incomescategoryassigned SET categoryName = :newCategoryIncome WHERE userId = :userId AND id = :categoryId';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_STR); 
            $stmt->bindValue(':newCategoryIncome', $newCategoryIncome, PDO::PARAM_STR); 

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            return $stmt->execute();
        }
         return false;
    }

    static function deleteIncomeForSpecificCategory($categoryArray) {
        $userId = $_SESSION['user_id'];

        $categoryId = $categoryArray['categoryId'];

        $sql = 'DELETE FROM incomes WHERE userId = :userId AND categoryIncomeId = :categoryId';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_STR); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }

    static function deleteIncomeForSpecificMethodPay($categoryArray) {
        $userId = $_SESSION['user_id'];

        $categoryId = $categoryArray['categoryId'];

        $sql = 'DELETE FROM incomes WHERE userId = :userId AND categoryIncomeId = :categoryId';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_STR); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }

    static function editIncome($array) {
        $userId = $_SESSION['user_id'];

        $idIncome = $array['idIncome'];
        $newValue = $array['newValue'];

        $sql = 'UPDATE incomes SET valueIncome = :newValue WHERE userId = :userId AND idIncome = :idIncome';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':idIncome', $idIncome, PDO::PARAM_INT); 
        $stmt->bindValue(':newValue', $newValue, PDO::PARAM_STR); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }

    static function deleteIncome($array) {
        
        $userId = $_SESSION['user_id'];
        $idIncome = $array['idIncome'];

        $sql = 'DELETE FROM incomes WHERE userId = :userId AND idIncome = :idIncome';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':idIncome', $idIncome, PDO::PARAM_INT); 

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->execute();
    }
}