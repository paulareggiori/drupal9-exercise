<?php


namespace Drupal\routes_and_controllers\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * A plugin manager for exercise plugins.
 *
 * The ExamplePluginManager class extends the DefaultPluginManager to provide
 * a way to manage exercise plugins. A plugin manager defines a new plugin type
 * and how instances of any plugin of that type will be discovered, instantiated
 * and more.
 *
 * The plugin manager is also declared as a service in
 * plugin_example.services.yml so that it can be easily accessed and used
 * anytime we need to work with example plugins.
 */
class ExamplePluginManager extends DefaultPluginManager {
  /**
   * Creates the discovery object.
   *§
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    // Replace the $subdirectory parameter with own value.
    // Example: Drupal\{module_name}\Plugin\Example\MyExamplePlugin
    $subdirectory = 'Plugin/Examples';

    // The name of the interface that plugins should adhere to.
    $plugin_interface = 'Drupal\routes_and_controllers\Plugin\ExampleInterface';

    // The name of the annotation class that contains the plugin definition.
    $plugin_definition_annotation_name = 'Drupal\routes_and_controllers\Annotation\ExamplePlugin';

    parent::__construct($subdirectory, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);

    // This allows the plugin definitions to be altered by an alter hook.
    $this->alterInfo('example_info');

    // This sets the caching method for our plugin definitions. Plugin
    // definitions are discovered by examining the $subdir defined above, for
    // any classes with an $plugin_definition_annotation_name. The annotations
    // are read, and then the resulting data is cached using the provided cache
    // backend. For our Sandwich plugin type, we've specified the @cache.default
    // service be used in the routes_and_controllers.services.yml file. The second
    // argument is a cache key prefix. Out of the box Drupal with the default
    // cache backend setup will store our plugin definition in the cache_default
    // table using the example_info key. All that is implementation details
    // however, all we care about is that caching for our plugin definition is
    // taken care of by this call.
    $this->setCacheBackend($cache_backend, 'example_plugins');
  }

}
