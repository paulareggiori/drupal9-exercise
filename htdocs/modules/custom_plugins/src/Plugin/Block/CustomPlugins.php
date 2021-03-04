<?php

namespace Drupal\custom_plugins\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an "Hello World" block.
 *
 * @Block(
 *   id = "custom_plugins",
 *   admin_label = @Translation("Hello world"),
 *   category = @Translation("Custom Plugins")
 * )
 */
class CustomPlugins extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works! I\'m a custom block'),
    ];
    return $build;
  }

}
