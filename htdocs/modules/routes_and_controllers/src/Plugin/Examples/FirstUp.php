<?php


namespace Drupal\routes_and_controllers\Plugin\Examples;

use Drupal\routes_and_controllers\Plugin\ExamplePluginBase;

/**
 * Provides a transformation of label into uppercase letters.
 *
 * @ExamplePlugin (
 *   id = "firstup",
 *   name = @Translation("Only First Letters Uppercase"),
 *   description = @Translation("Change the first letter of all words to uppercase"),
 * )
 */
class FirstUp extends ExamplePluginBase {

  public function transform($text) {
    // Insert code to actually transform the text.
    $text = ucwords($text);
    return $text;
  }
}
