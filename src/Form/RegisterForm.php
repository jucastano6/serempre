<?php
/**
 * @file
 * Contains Drupal\serempre\Form\RegisterForm.
 */

namespace Drupal\serempre\Form;

use Drupal\Core\Form\FormBase ;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\serempre\Controller\SerempreController;
use Drupal\Core\Ajax\InvokeCommand;


class RegisterForm extends FormBase  {
   /**
   * Returns unique ID 
   */
   public function getFormId() {
    return 'registrar_form';
  }

  /*
  * Build a drupal form 
  */
  public function buildForm(array $form, FormStateInterface $form_state) { 
   $form['massage'] = [
    '#type' => 'markup',
    '#markup' => '<div class="result_message"></div>',
  ];

  $form['nombre_usuario'] = [
    '#type' => 'textfield',
    '#title' => 'Nombre del usuario',
    '#description' => 'Ingrese el nombre del usuario a registrar',      
  ];

  /*
  * Makes a custom button with ajax to manipulate the information
  */
  $form['actions'] = [
    '#type' => 'button',
    '#value' => $this->t('Registrar'),
    '#ajax' => [
      'callback' => '::dataValidation',
    ]
  ];
  
  return $form;

}

  /*
  * Does a validation for the textfield 'nombre_usuario'
  * and returns error message or sends to controller to save it
  */
  public function dataValidation(array &$form, FormStateInterface $form_state) {
 
  $controller = new SerempreController();
  $name = $form_state->getValue('nombre_usuario');
  $status = 1;
  if ( $name != "" ) {
    
    $nameValidation = $controller->validateUser($name);
  
    if ( strlen($name) < 6 ) {
      $message = "El nombre es muy corto";
      $status = 0;  
    }

    if ($nameValidation && $status) {
      $message = "El nombre ya se encuentra registrado";
      $status = 0;
    }

    if ($status) {      
      $message = $controller->registerUser($name);
    }
  }
  else
  {
    $message = "Por favor ingrese un nombre";
  }

  /*
  * build a modal with twig template 'modal-resultados'
  */
  $build = [
    '#theme' => 'modal-resultados',
    '#mensaje' => $message,        
  ];

  $rendered = \Drupal::service('renderer')->renderRoot($build); 
  
  /*
  * prepares an ajax response with the modal
  */
  $response = new AjaxResponse();
  $response->addCommand(
    new HtmlCommand(
      '.result_message',
      $rendered
    )
  );
  /*
  * invoke a JS function to execute the modal window
  */
  $response->addCommand(new InvokeCommand(NULL,'respuestaRegistro', ['']));
  return $response;
}

  /**
   * Form submission handler.
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {     

  }
}
