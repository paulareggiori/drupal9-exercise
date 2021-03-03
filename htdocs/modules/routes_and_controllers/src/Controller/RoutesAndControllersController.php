<?php

/**
 * @file
 * Contains Drupal\routes_and_controllers\Controller\RoutesAndControllersController.
 */

namespace Drupal\routes_and_controllers\Controller;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\node\NodeInterface;
use Drupal\routes_and_controllers\Access\CustomAccess;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;

/**
 * Class RoutesAndControllersController
 *
 * Returns responses for routes and controllers routes.
 */

class RoutesAndControllersController extends ControllerBase {

  /**
   * The say something here
   *
   * @var blablabla
   */
  private $user;

  /**
   * The say something here
   *
   * @var blablabla
   */
  private $title;

  /**
   * Constructs the container
   *
   * @param string $user user currently logged
   */
  public function __construct(AccountProxyInterface $user) {
    $this->user = $user;

  }

  /**
   * Return an array of contents to be rendered.
   *
   * @return array
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

  /**
   * Return a string with the current system time.
   *
   * @return string current time
   */
  public function getTime() {
    $time =  new DateTimePlus();

    return $time->format('h:i:s d-m-Y');
  }

  /**
   * Return an array of contents to be rendered.
   *
   * @return array
   */
  public function showUser() {
    $build['content'] = [
      '#type' => 'item',
      #put inside the t function with placeholders
      '#markup' => 'Welcome '. $this->user->getAccountName() .'!',
    ];

    return $build;
  }

  /**
   * Return an array of contents to be rendered using the @param $fruit
   *
   * @oparam string $fruit
   * @return array
   */
  public function favoriteFruit($fruit) {
    $build['content'] = [
      '#type' => 'item',
      '#markup' => 'Welcome '. $this->user->getAccountName() .'!<br>'
    . 'I heard somewhere you like '. $fruit . '.',
    ];

    return $build;
  }

  /**
   * Return a string of the title of the node id using the @param $node
   *
   * @oparam int $node
   * @return string
   */
  public function findTitle(NodeInterface $node) {

    return $node->getTitle();
  }

  /**
   * Return an array of contents to be rendered.
   *
   * @return array
   */
  public function findNode(NodeInterface $node) {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => 'I\'m not actually this'. $node->getTitle() . ' page! ;)',
    ];

    return $build;
  }

  public function useCustomAccess() {
    $build['content'] = [
      '#type' => 'item',
      '#markup' => 'You can see me! YAAAAY',
    ];

      return $build;
  }

  /**
   * Creates a container to user certain services.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return service $user
   */
  public static function create(ContainerInterface $container) {
    $user= $container->get('current_user');

    return new static($user);
  }


}
