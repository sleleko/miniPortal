<?php

namespace App\Service;

use App\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
  /** @var Manager $capsule */
  public $capsule;
  /** @var Builder $capsule */
  public $schema;


  public function init()
  {
    $container = new Container();
    $this->capsule = $container->capsule;
    $this->schema = $container->capsule->schema();
  }
}