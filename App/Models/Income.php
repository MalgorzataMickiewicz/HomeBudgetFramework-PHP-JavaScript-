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
        $userId = $_SESSION['user_id'];
        $categoryIncomeId = '1';

        $categoryIncome = $this->categoryIncome;

        //get value of choosen category id from database
        $categoryIdString = static::getCategoryId($categoryIncome);

        if ($categoryIdString) {
            $categoryIdIntiger = (int)$categoryIdString;

            $sql = 'INSERT INTO incomes (userId, dateIncome, valueIncome, categoryIncomeId, commentIncome)
            VALUES (:userId, :dateIncome, :valueIncome, :categoryIdIntiger, :commentIncome)';
        }

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT); 
        $stmt->bindValue(':dateIncome', $this->dateIncome, PDO::PARAM_STR); 
        $stmt->bindValue(':valueIncome', $this->valueIncome, PDO::PARAM_STR);
        $stmt->bindValue(':categoryIdIntiger', $categoryIncomeId, PDO::PARAM_STR);
        $stmt->bindValue(':commentIncome', $this->commentIncome, PDO::PARAM_STR);

        return $stmt->execute();
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
}