<?php
/**
 * @file
 * Provides \Drupal\routes_and_controllers\Plugin\ExampleInterface
 *
 * When defining a new plugin type you need to define an interface that all
 * plugins of the new type will implement. This ensures that consumers of the
 * plugin type have a consistent way of accessing the plugin's functionality. It
 * should include access to any public properties, and methods for accomplishing
 * whatever business logic anyone accessing the plugin might want to use.
 *
 * For example, an image manipulation plugin might have a "process" method that
 * takes a known input, probably an image file, and returns the processed
 * version of the file.
 *
 * In our case we'll define methods for accessing the human readable description
 * of a sandwich and the number of calories per serving. As well as a method for
 * ordering a sandwich.
 */

namespace Drupal\routes_and_controllers\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * An interface for all Example type plugins.
 */
class ExampleInterface extends PluginInspectionInterface {

  /**
   * Provide the name of the plugin.
   *
   * @return string
   *   A string name of the plugin.
   */
  public function name();

  /**
   * Provide a description of the plugin.
   *
   * @return string
   *   A string description of the plugin.
   */
  public function description();

  /**
   * Provide the tranformation according to the plugin id.
   *
   * @return string
   *   The lable after the tranformation.
   */
  public function transform(string $label);

}
