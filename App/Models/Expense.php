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

}