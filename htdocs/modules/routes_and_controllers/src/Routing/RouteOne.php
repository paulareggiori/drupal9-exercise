<?php
/**
 * @file
 * Contains Drupal\routes_and_controllers\Routing\RouteOne.
 */

namespace Drupal\routes_and_controllers\Routing;

use Drupal\Component\Datetime\DateTimePlus;
use Symfony\Component\Routing\Route;

/**
 * Class RouteOne
 *
 * Returns a route.
 */
class RouteOne {
  /**
   * Create a dynamic route.
   *
   * @return array with a route
   */
  public function routes() {

    $routes = [];
    // Declares a single route under the name 'example.content'.
    // Returns an array of Route objects.
    $routes['one.content'] = new Route(
    // Path to attach this route to:
      '/one',
      // Route defaults:
      [
        '_controller' => '\Drupal\routes_and_controllers\Controller\RoutesAndControllersController::build',
        '_title_callback' => 'Drupal\routes_and_controllers\Controller\RoutesAndControllersController::getTime',
      ],
      // Route requirements:
      [
        '_permission' => 'access content',
      ]
    );
    return $routes;
  }

}
