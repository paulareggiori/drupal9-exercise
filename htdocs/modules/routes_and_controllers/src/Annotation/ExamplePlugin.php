<?php

/**
 * @file
 * Contains \Drupal\routes_and_controllers\Annotation\ExamplePlugin.
 *
 * Provides an example of how to define a new annotation type for use in
 * defining a plugin type. Demonstrates documenting the various properties that
 * can be used in annotations for plugins of this type.
 */

namespace Drupal\routes_and_controllers\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Sandwich annotation object.
 *
 * @see \Drupal\routes_and_controllers\Plugin\ExamplePluginManager
 * @see plugin_api
 *
 * Note that the "@ Annotation" line below is required and should be the last
 * line in the docblock. It's used for discovery of Annotation definitions.
 *
 * @Annotation
 */
class ExamplePlugin extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The name of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $name;

  /**
   * A brief, human readable, description of the plugin type.
   *
   * This property is designated as being translatable because it will appear
   * in the user interface. This provides a hint to other developers that they
   * should use the Translation() construct in their annotation when declaring
   * this property.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * The number of calories per serving of this sandwich type.
   *
   * This property is a float value, so we indicate that to other developers
   * who are writing annotations for a Sandwich plugin.
   *
   * @var int
   */
}
