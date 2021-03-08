<?php


namespace Drupal\routes_and_controllers\Plugin;


use Drupal\Component\Plugin\PluginBase;

/**
 * Provides \Drupal\routes_and_controllers\Plugin\ExamplePluginBase.
 *
 * This is a helper class which makes it easier for other developers to
 * implement example plugins in their own modules. In ExamplePluginBase we provide
 * some generic methods for handling tasks that are common to pretty much all
 * example plugins. Thereby reducing the amount of boilerplate code required to
 * implement an example plugin.
 *
 * In this case both the description and calories properties can be read from
 * the @Sandwich annotation. In most cases it is probably fine to just use that
 * value without any additional processing. However, if an individual plugin
 * needed to provide special handling around either of these things it could
 * just override the method in that class definition for that plugin.
 *
 * We intentionally declare our base class as abstract, and skip the order()
 * even if they are using our base class, developers will always be required to
 * define an order() method for their custom sandwich type.
 *
 * @see \Drupal\routes_and_controllers\Annotation\ExamplePlugin
 * @see \Drupal\routes_and_controllers\Plugin\ExampleInterface
 */
abstract class ExamplePluginBase extends PluginBase implements ExampleInterface {

  /**
   * {@inheritdoc}
   */
  public function name() {
    return $this->pluginDefinition['name'];
  }

  /**
   * {@inheritdoc}
   */
  public function description() {
    return $this->pluginDefinition['description'];
  }

  /**
   * {@inheritdoc}
   */
  public function transform($label) {
    return $label;
  }


}
