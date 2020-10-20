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
            $value = $this->valueIncome;

            //get value of choosen category id from database
            $categoryIdString = static::getCategoryId($categoryIncome);

            if ($categoryIdString) {
                $categoryIdIntiger = (int)$categoryIdString;
                
                //get value withouts comma and round to 2
                $valueDot = static::getValueWithDot($value);

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

        // if ($this->categoryIncome == '') {
          //  $this->errors[] = 'Wybierz kategorię';
        //}
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
        $valueDot = ceil($value * $mult) / $mult;

        return $valueDot;
    }

}