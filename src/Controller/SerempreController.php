<?php
/**
 * @file
 * Contains Drupal\serempre\Controller\SerempreController.
 */

namespace Drupal\serempre\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\serempre\Model\MyusersModel;
use Drupal\serempre\Model\AccesslogModel;
use Symfony\Component\HttpFoundation;

class SerempreController extends ControllerBase
{		

 /*
 * Sends name to model to validate the existence in DB
 */
 public function validateUser($name){
  $record = MyusersModel::validateUserDB($name);
  return $record;
}

 /**
 * Sends name to model to create a user record
 */
 public function registerUser($name){
  $idUser=MyusersModel::registerUserDB($name);

  if ( $idUser['status'] ) {
    $message = "Se ha realizado el registro para : ".$name.' con el ID: '.$idUser['msg'];
  }
  else{
    $message = "Ha ocurrido un error, por favor intente de nuevo. Descripción error: ".$idUser['msg'];
  }
  return $message;
}

/*
* Gets users from model to show on table theme with pagination
*/
public function usersListPagination(){  
  $header = array(      
    array('data' => 'ID'),
    array('data' => 'Nombre'),
  );
  
  $userList=MyusersModel::usersListPaginationDB($header);
  $rows = array();

  foreach($userList as $row) {
    $rows[] = array('data' => array(
        'id' => $row->id,
        'name' => $row->nombre,
      ));
  }  

  $build['table'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $rows,
  );
  
  $build['#cache']['max-age'] = 0;
  $build['#cache']['contexts'] = [];
  $build['#cache']['tags'] = [];
  
  $build['pager'] = array(
    '#type' => 'pager'
  );

  return $build;
}

/*
* Register an event from login & entity_insert hooks
*/
public function eventRegister($tipo,$uid){

  $data = array(
    'fecha' => \Drupal::time()->getRequestTime(),
    'ip' => \Drupal::request()->getClientIp(),
    'uid' => $uid,
    'tipo_log' => $tipo
  );

  $record = AccesslogModel::eventRegisterDB($data);    

  if ( $record['status']  ) {
    \Drupal::logger('access_log')->notice('Se ha realizado un regitro en la tabla access_log');
  }
  else{
    \Drupal::logger('access_log')->error('Ha ocurrido un error intentando insertar información. Descripción: '.$record['msg']);
  }

}

/*
* Prepares a batch process to import information from CSV file 
*/
public function importCSV($path){
  /*
  * Creates an array and moves every csv line / user.
  */
  $users=array();
  $fp = fopen($path, "r");    
  while (!feof($fp)) {
    $line = fgets($fp);
    array_push($users, $line);    
  }
  fclose($fp);

  /*
  * Creates an array with the operations to batch
  */
  $operations=array();
  foreach ($users as $user) {
    $operations[] = array('\Drupal\serempre\Utilities\UserImport::import', array($user));    
  } 

  /*
  * Creates an array with the batch information necessary to works
  */
  $batch = array(
    'title' => 'Importando',
    'operations' => $operations,
    'finished' => '\Drupal\serempre\Utilities\UserImport::finishBatch',    
    'progress_message' => t('Importando el usuario # @current de @total en total'),
  );

  batch_set($batch);
}

/*
* Generates an excel file without libraries with all user records from 'Myusers' table
*/
public function downloadExcel() {
  $response = new Response();
  $filename = 'export_'.date('d-m-y').'.xlsx';     
  $response->headers->set('Content-Type', 'application/vnd.ms-excel');
  $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
  $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
  <head>    
  <xml>
  <x:ExcelWorkbook>
  <x:ExcelWorksheets>
  <x:ExcelWorksheet>
  <x:Name>Listado Usuarios</x:Name>
  <x:WorksheetOptions>
  <x:Print>
  <x:ValidPrinterInfo/>
  </x:Print>
  </x:WorksheetOptions>
  </x:ExcelWorksheet>
  </x:ExcelWorksheets>
  </x:ExcelWorkbook>
  </xml>    
  </head>
  <body>
  <table><tr><td>ID</td><td>Nombre</td></tr>';  
    
  $userList=MyusersModel::userListGeneral();    
  foreach($userList as $row) {
    $html = '<tr>';
    $html .= '<td>'.$row->id.'</td>';
    $html .= '<td>'.$row->nombre.'</td>';
    $html .= '</tr>';
    $data .= $html;
  }
  $data .= '</table>
  </body></html>';
  $response->setContent($data);    
  return $response;
}

/*
* Generates an excel file with phpspreadsheet library with all user records from 'Myusers' table
*/
/*
  public function downloadExcel() {
    $response = new Response();
    $filename = 'export_'.date('d-m-y').'.xls';     
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition', 'attachment; filename=test.xlsx');
    $spreadsheet = new Spreadsheet();
    $spreadsheet->getProperties()
      ->setCreator('Serempre')      
      ->setTitle("Listado de usuarios")
      ->setLastModifiedBy('Julian castaño');      
    $spreadsheet->setActiveSheetIndex(0);
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle('Listado de usuarios');
    $userList=MyusersModel::userListGeneral(); 
    $i=2;
    $worksheet->getCell('A1')->setValue('ID');
    $worksheet->getCell('B1')->setValue('Nombre');       
    foreach($userList as $row) {
      $worksheet->getCell('A'.$i)->setValue($row->id);
      $worksheet->getCell('B'.$i)->setValue($row->nombre);      
    }
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');    
    ob_start();
    $writer->save('php://output');
    $content = ob_get_clean();
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);
    $response->setContent($content);
    return $response;
}
*/

}
?>