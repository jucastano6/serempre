<?php
/**
 * @file
 * Contains Drupal\serempre\Utilities\UserImport
 */

namespace Drupal\serempre\Utilities;

use Drupal\serempre\Controller\SerempreController;

/**
 * Contains the processes to makes a batch operation
 */
class UserImport{

 /**
 * Function in charge of validating the existence of a name.
 * Register the name if the validation is false.
 */ 
 public static function import($data,&$context){
  $controller = new SerempreController();
  if ( $controller->validateUser($data) ) {
    $message="Ya existe un usuario con el nombre: ".$data;
  }
  else {    
    $message=$controller->registerUser($data);
  }        
  $context['results'][]=$message;
}

/**
 * Function responsible for displaying the results of batch operations
 */
public static function finishBatch($success, $results, $operations) { 
  if ($success) {
    drupal_set_message(t('Se han realizado las siguientes operaciones (%count en total) : ', array('%count' => count($results))));  
    foreach ($results as $item) {
      drupal_set_message($item,'status');  
    }
  } 
  else {
    drupal_set_message('Finalizó el proceso pero con errores, por favor verifique el log','error');  
  }
}

}

?>