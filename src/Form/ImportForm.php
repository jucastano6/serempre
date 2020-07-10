<?php
/**
 * @file
 * Contains Drupal\serempre\Form\ImportForm.
 */
namespace Drupal\serempre\Form;
use Drupal\Core\Form\FormBase ;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\serempre\Controller\SerempreController;
use Drupal\Core\Ajax\InvokeCommand;



class ImportForm extends FormBase  {
  /**
  * Returns an unique id
  */
  public function getFormId() {
    return 'import_form';
  }
  
  /*
  * Build a drupal form 
  */
  public function buildForm(array $form, FormStateInterface $form_state) { 


    $form['archivo_csv']= [
      '#type' => 'managed_file',
      '#name' => 'image',
      '#title' => 'Archivo CSV',
      '#size' => 20,
      '#description' => 'Por favor suba el archivo CSV que desea importar',  
      '#upload_validators' => array('file_validate_extensions' => ['csv']),
      '#upload_location' => 'public://',
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Guardar'),
    );

    return $form;
    
  }

  /**
   * Form submission handler.
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   *
   * Uploads a CSV file and calls a controller to start a batch importation
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {     
   try{                        
    $fid = $form_state->getValue(['archivo_csv', 0]);
    
    if (!is_null($fid)) {
      $file = \Drupal\file\Entity\File::load($fid);
      $path = \Drupal::service('file_system')->realpath($file->getFileUri());
      $serempre = new SerempreController();
      $serempre->importCSV($path);             
    }
    else{
      drupal_set_message('Por favor seleccione un archivo', "error");  
    }

  } catch (EXception $e){
    drupal_set_message(t("Se ha producido un error al actualizar los valores de configuración."), "error");
  }
}
}
