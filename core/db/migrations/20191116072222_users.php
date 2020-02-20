<?php

use App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class Users extends Migration
{

    public function up()
    {
        $this->schema->create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->json('scope')->nullable();
            $table->timestamps();
        });

        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password')->nullable();
            $table->string('fullname')->nullable();
            $table->string('email')->nullable();
            $table->char('phone', 10)->nullable();
            $table->integer('role_id')->unsigned()->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')->on('user_roles')
                ->onUpdate('restrict')
                ->onDelete('set null');
        });

        $this->schema->create('user_tokens', function (Blueprint $table) {
            $table->string('token')->primary();
            $table->integer('user_id')->unsigned();
            $table->boolean('active')->default(1);
            $table->string('ip')->nullable();
            $table->timestamp('valid_till')->nullable()->index();
            $table->timestamps();

            $table->index(['token', 'user_id', 'active']);

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('cascade');
        });
    }


    public function down()
    {
        $this->schema->drop('user_tokens');
        $this->schema->drop('users');
        $this->schema->drop('user_roles');
    }

}
