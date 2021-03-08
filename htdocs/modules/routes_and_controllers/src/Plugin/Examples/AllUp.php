<?php


namespace Drupal\routes_and_controllers\Plugin\Example;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\routes_and_controllers\Plugin\ExamplePluginBase;

/**
 * Provides a transformation of label into uppercase letters.
 *
 * @Plugin (
 *   id = "allupper",
 *   name = @Translation("All Uppercase"),
 *   description = @Translation("Change letter to all upper case"),
 * )
 */
class AllUp extends ExamplePluginBase implements ContainerFactoryPluginInterface {

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public function transform($label) {
    // Insert code to query the international chocolate database for the current
    // going rate of cocoa and then determine the price per scoop of Chocolate
    // ice cream, or simply return the default price if we can't determine one.
    //
    // Magic ...
    //
    return $label;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerFactoryPluginInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static ($configuration, $plugin_id, $plugin_definition);
  }
}
