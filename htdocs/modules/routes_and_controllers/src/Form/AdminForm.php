<?php
/**
 * @file
 * Contains Drupal\routes_and_controllers\Form\AdminForm.
 */

namespace Drupal\routes_and_controllers\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AdminForm
 *
 * Returns a form.
 */
class AdminForm extends ConfigFormBase{
  /**
   * Returns form id.
   *
   * @return string form id
   */
  public function getFormId() {
    return 'routes_and_controllers_admin_form';
  }

  protected function getEditableConfigNames() {
    return [
      'admin_form.settings',
    ];
  }

  /**
   * Constructs the form to be displayed.
   *
   * @return array with form fields to be rendered
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('admin_form.settings');
    $form['field1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field 1'),
      '#default_value' => $config->get('field1'),
    ];
    $form['field2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field 2'),
      '#default_value' => $config->get('field2'),
    ];
    $form['colour_select'] = [
      '#type' => 'radios',
      '#title' => $this->t('Pick a colour'),
      '#options' => [
        'blue' => $this->t('Blue'),
        'white' => $this->t('White'),
        'black' => $this->t('Black'),
        'other' => $this->t('Other'),
      ],
      '#attributes' => [
        //define static name and id so we can easier select it
        // 'id' => 'colour_select',
        'name' => 'colour_select',
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Get the contents and saves them.
   *
   * @return method save the contents.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save items
    $this->config('admin_form.settings')
      ->set('field1', $form_state->getValue('field1'))
      ->save();

    $this->config('admin_form.settings')
      ->set('field2', $form_state->getValue('field2'))
      ->save();

    $this->config('admin_form.settings')
      ->set('colour_select', $form_state->getValue('colour_select'))
      ->save();

    parent::submitForm($form, $form_state);

  }

}
