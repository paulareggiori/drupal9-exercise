<?php


namespace Drupal\routes_and_controllers\Plugin\Examples;

use Drupal\routes_and_controllers\Plugin\ExamplePluginBase;

/**
 * Provides a transformation of label into uppercase letters.
 *
 * @ExamplePlugin (
 *   id = "allupper",
 *   name = @Translation("All Uppercase"),
 *   description = @Translation("Change letter to all uppercase"),
 * )
 */
class AllUp extends ExamplePluginBase {

  public function transform($text) {
    // Insert code to actually transform the text.
    $text = strtoupper($text);
    return $text;
  }
}

