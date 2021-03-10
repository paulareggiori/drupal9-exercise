<?php


namespace Drupal\routes_and_controllers\Plugin\Examples;


use Drupal\routes_and_controllers\Plugin\ExamplePluginBase;

/**
 * Provides a transformation of label into uppercase letters.
 *
 * @ExamplePlugin (
 *   id = "reverse",
 *   name = @Translation("Reverse Letters"),
 *   description = @Translation("Reverse letters of all words"),
 * )
 */
class Reverse  extends ExamplePluginBase {

  public function transform($text) {
    // Insert code to actually transform the text.
    $text = strrev($text);
    return $text;
  }
}
