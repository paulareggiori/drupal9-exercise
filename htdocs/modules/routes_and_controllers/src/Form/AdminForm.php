<?php
/**
 * @file
 * Contains Drupal\routes_and_controllers\Form\AdminForm.
 */

namespace Drupal\routes_and_controllers\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\routes_and_controllers\Plugin\ExamplePluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AdminForm
 *
 * Returns a form.
 */
class AdminForm extends ConfigFormBase {

  /**
   * The example plugin manager.
   *
   * We use this to get all of the example plugins.
   *
   * @var \Drupal\routes_and_controllers\Plugin\ExamplePluginManager
   */
  protected $manager;

  /**
   * We use this to get all of the example plugins definitions.
   *
   * @var array of all plugin definitions
   */
  protected $plugin_definitions;

  /**
   * Constructor.
   *
   * @param \Drupal\routes_and_controllers\Plugin\ExamplePluginManager $manager
   *   The example plugin manager service. We're injecting this service so that
   *   we can use it to access the example plugins.
   */
  public function __construct(ExamplePluginManager $manager) {
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   *
   * Override the parent method so that we can inject our sandwich plugin
   * manager service into the controller.
   *
   * For more about how dependency injection works read https://www.drupal.org/node/2133171
   */
  public static function create(ContainerInterface $container) {
    $manager= $container->get('plugin_manager_example');
    return new static($manager);
  }

  /**
   * Returns form id.
   *
   * @return string form id
   */
  public function getFormId() {
    return 'routes_and_controllers';
  }

  protected function getEditableConfigNames() {
    return [
      'routes_and_controllers.settings',
    ];
  }

  /**
   * Constructs the form to be displayed.
   *
   * @return array with form fields to be rendered
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('routes_and_controllers.settings');
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
    $form['transform'] = [
      '#type' => 'select',
      '#title' => $this->t('Show text in form'),
      '#attributes' => [
        //define static name and id so we can easier select it
        // 'id' => 'colour_select',
        'name' => 'text_transform',
      ],
    ];

    // Get the list of all the plugins defined on the system from the
    // plugin manager.
    $plugin_definitions = $this->manager->getDefinitions();

    // Let's output a list of the plugin definitions we now have.
    $form['transform']['options'] = array();
    foreach ($plugin_definitions as $plugin_definition) {
      // Here we use various properties from the plugin definition.
      $form['transform']['options'] = t("@id (name: @name)", array(
        '@id' => $plugin_definition['id'],
        '@name' => $plugin_definition['name'],
      ));
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * Get the contents and saves them.
   *
   * @return method save the contents.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save items
    $this->config('routes_and_controllers.settings')
      ->set('field1', $form_state->getValue('field1'))
      ->save();

    $this->config('routes_and_controllers.settings')
      ->set('field2', $form_state->getValue('field2'))
      ->save();

    $this->config('routes_and_controllers.settings')
      ->set('colour_select', $form_state->getValue('colour_select'))
      ->save();

    $this->config('routes_and_controllers.settings')
      ->set('text_transform', $form_state->getValue('text_transform'))
      ->save();

    parent::submitForm($form, $form_state);
  }


}
