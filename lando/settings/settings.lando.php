<?php
// @codingStandardsIgnoreFile
/**
 * @file
 * Settings for docker.
 */

$settings['hash_salt'] = 'BK3d5nLc4Mnz0OYNWdd4Uo0ZWRvxH5Yblxn7D6u3Z9kz_F7cPck2FXBZ8xVu1xRyIVdM_I1keQ';

// Trusted host patterns.
// Note: "apache" is important because this is the local name which DTT tests
// will use for connecting to the existing site in the docker network.
$settings['trusted_host_patterns'] = [
  '^appserver',
  '^.+\.lndo.site$',
];

// Guardian settings.
$settings['guardian_mail'] = 'd.system@intracto.com';

// Disable twig caching.
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';

// Disable CSS/JS.
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

// Enable services.
$settings['container_yamls'][] = '../settings/services.local.yml';

// Set the active environment (dev|test|acc|prod)
$active_environment = 'dev';

// Excluded should always be active.
$config['config_split.config_split.excluded']['status'] = TRUE;
$config['config_split.config_split.' . $active_environment]['status'] = TRUE;

// Show verbose errors on screen.
$config['system.logging']['error_level'] = 'verbose';

// Elasticsearch settings.
$config['elasticsearch_connector.cluster.elasticsearch']['url'] = 'http://elasticsearch:9200';

// Private files.
$settings['file_private_path'] =  '/app/private';

if (!\Drupal\Core\Installer\InstallerKernel::installationAttempted() && extension_loaded('redis')) {
  // Manually add the classloader path, this is required for the container cache bin definition below
  // and allows to use it without the redis module being enabled.
  try {
    $class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src');
  }
  catch (\Exception $e) {
    die("Something went wrong with autoloading redis");
  }

  $settings['redis.connection']['interface'] = 'PhpRedis';
  $settings['redis.connection']['host'] = 'cache';

  // Set Redis as the default backend for any cache bin not otherwise specified.
  $settings['cache']['default'] = 'cache.backend.redis';
  $settings['cache_prefix']['default'] = 'drupal-redis';

  // Apply changes to the container configuration to better leverage Redis.
  // This includes using Redis for the lock and flood control systems, as well
  // as the cache tag checksum. Alternatively, copy the contents of that file
  // to your project-specific services.yml file, modify as appropriate, and
  // remove this line.
  $settings['container_yamls'][] = $app_root . '/modules/contrib/redis/example.services.yml';

  // Allow the services to work before the Redis module itself is enabled.
  $settings['container_yamls'][] = $app_root . '/modules/contrib/redis/redis.services.yml';

  // Use redis for container cache.
  // The container cache is used to load the container definition itself, and
  // thus any configuration stored in the container itself is not available
  // yet. These lines force the container cache to use Redis rather than the
  // default SQL cache.
  $settings['bootstrap_container_definition'] = [
    'parameters' => [],
    'services' => [
      'redis.factory' => [
        'class' => 'Drupal\redis\ClientFactory',
      ],
      'cache.backend.redis' => [
        'class' => 'Drupal\redis\Cache\CacheBackendFactory',
        'arguments' => [
          '@redis.factory',
          '@cache_tags_provider.container',
          '@serialization.phpserialize'
        ],
      ],
      'cache.container' => [
        'class' => '\Drupal\redis\Cache\PhpRedis',
        'factory' => ['@cache.backend.redis', 'get'],
        'arguments' => ['container'],
      ],
      'cache_tags_provider.container' => [
        'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
        'arguments' => ['@redis.factory'],
      ],
      'serialization.phpserialize' => [
        'class' => 'Drupal\Component\Serialization\PhpSerialize',
      ],
    ],
  ];
}

// The database settings are added below by the init.sh script.
