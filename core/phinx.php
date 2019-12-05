<?php

require 'vendor/autoload.php';

$container = new \App\Container();
$config = $container->capsule->getConnection()->getConfig();

return [
  'paths' => [
    'migrations' => BASE_DIR . '/core/db/migrations',
    'seeds' => BASE_DIR . '/core/db/seeds',
  ],
  'migration_base_class' => 'App\Service\Migration',
  'environments' => [
    'default_migration_table' => $config['prefix'] . 'migrations',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => $config['driver'],
      'host' => $config['host'],
      'name' => $config['database'],
      'user' => $config['username'],
      'pass' => $config['password'],
      'port' => $config['port'],
      'charset' => $config['charset'],
      'collation' => $config['collation'],
      'table_prefix' => $config['prefix'],
    ],
  ],
];