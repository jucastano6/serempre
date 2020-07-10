<?php
/**
 * @file
 * Contains Drupal\serempre\Model\AccesslogModel.
 */

namespace Drupal\serempre\Model;

use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Database\Database;


class AccesslogModel
{
 /**
 * Insert a record to access_log table and returns lastID inserted
 * or error message from catch
 */
  public static function eventRegisterDB($data){
    try {
      $db = \Drupal::database();
      $msg = $db->insert('access_log')
      ->fields([
        'fecha',        
        'ip',
        'uid',
        'tipo_log'
      ])
      ->values($data)
      ->execute();
      $msg=['status' => 1, 'msg' => $msg];
    } catch (Exception $e) {
      $msg=['status' => 0, 'msg' => $e->getMessage()];      
    }    
    return $msg; 
  }
}
