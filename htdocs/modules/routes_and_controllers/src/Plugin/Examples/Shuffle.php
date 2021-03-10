<?php


namespace Drupal\routes_and_controllers\Plugin\Examples;

use Drupal\routes_and_controllers\Plugin\ExamplePluginBase;


/**
 * Provides a transformation of label into uppercase letters.
 *
 * @ExamplePlugin (
 *   id = "shuffle",
 *   name = @Translation("Shuffle Letters"),
 *   description = @Translation("Shuffle letters of all words"),
 * )
 */
class Shuffle extends ExamplePluginBase {

  public function transform($text) {
    // Insert code to actually transform the text.
    $text = str_shuffle($text);
    return $text;
  }
}
