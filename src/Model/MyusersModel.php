<?php
/**
 * @file
 * Contains Drupal\serempre\Model\MyusersModel.
 */

namespace Drupal\serempre\Model;

use Drupal\Core\Database\Database;

class MyusersModel
{
 /**
 * Insert user DB and returns lastID inserted
 * or error message from catch
 */
  public static function registerUserDB($name){
    try {
      $db = \Drupal::database();
      $msg = $db->insert('myusers')
      ->fields([
        'nombre',        
      ])
      ->values(array(
        $name,        
      ))
      ->execute();      
      $msg=['status' => 1, 'msg' => $msg];
    } catch (Exception $e) {
      $msg=['status' => 0, 'msg' => $e->getMessage()];  
    }    
    return $msg;
  }

 /**
 * Search an user from DB and returns count if exist
 */
  public static function validateUserDB($name){   
    try {
      $db = \Drupal::database();  
      $msg = \Drupal::database()->select('myusers', 'm')      
      ->condition('nombre',$name,'=')
      ->countQuery()      
      ->execute()  
      ->fetchField(); 
    } catch (Exception $e) {
      $msg = $e->getMessage();
    }              
    return $msg;
  }

  /**
  * Gets users with limit of 4 records to pager
  */
  public static function usersListPaginationDB($header){   
    $db = \Drupal::database();
    $query = $db->select('myusers','c');
    $query->fields('c', array('nombre','id'));       
    $query = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
                        ->limit(4);
    $result = $query->execute();
    return $result;
  }

  /**
  * Gets all users without limit
  */
  public static function userListGeneral(){   
    $db = \Drupal::database();
    $query = $db->select('myusers','c');
    $query->fields('c', array('nombre','id'));          
    $result = $query->execute();
    return $result;
  }


}
