<?php
/**
 * @file
 * Contains Drupal\serempre\Form\ConfigForm.
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
use Drupal\Core\Url;



class ConfigForm extends FormBase  {
   /*
   * Returns unique id
   */
  public function getFormId() {
    return 'config_form';
  }

  /*
  * Build a drupal form 
  */
  public function buildForm(array $form, FormStateInterface $form_state) { 

  $url_registro = Url::fromRoute('serempre.config.registrar', [], ['absolute' => TRUE]);
  $url_consulta = Url::fromRoute('serempre.config.consultar', [], ['absolute' => TRUE]);
  $url_importar = Url::fromRoute('serempre.config.importar', [], ['absolute' => TRUE]);
  $url_exportar = Url::fromRoute('serempre.config.exportar', [], ['absolute' => TRUE]);
  
  $markup = [
   '#type' => 'markup',
   '#markup' => '<br><hr><br>',
  ];

  $form['markup_titulo'] =  [
   '#type' => 'markup',
   '#markup' => '<h3>Módulo Serempre</h3>',
  ];

  $form['markup_descripcion'] =  [
   '#type' => 'markup',
   '#markup' => '<p>Bienvenido al módulo de Serempre, por favor de click sobre la opción de su interés</p>',
  ];


  $form['link_registrar'] = [
   '#title' => 'Registrar Usuario',
   '#type' => 'link',
   '#url' => $url_registro,
  ];

  $form['link_registrar_markup'] = $markup;

  $form['link_consultar'] = [
   '#title' => 'Consultar Usuarios',
   '#type' => 'link',
   '#url' => $url_consulta,
  ];

  $form['link_consultar_markup'] = $markup;

  $form['link_importar'] = [
   '#title' => 'Importar Usuario',
   '#type' => 'link',
   '#url' => $url_importar,
  ];

  $form['link_importar_markup'] = $markup;

  $form['link_exportar'] = [
   '#title' => 'Exportar Usuario',
   '#type' => 'link',
   '#url' => $url_exportar,
  ];

  $form['link_exportar_markup'] = $markup;

  return $form;
    
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
