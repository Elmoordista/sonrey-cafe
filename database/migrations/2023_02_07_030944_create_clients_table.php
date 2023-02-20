<?php

use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('email', 45)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('number')->nullable();
            $table->text('name');
            $table->text('image')->nullable();
            $table->integer('role')->nullable();
            $table->text('username');
            $table->text('password');
            $table->timestamps();
        });

        Client::create(
            [
                "email" => "adminaccount@gmail.com",
                "name" => 'manual order',
                "image" => null,
                "role" => 10,
                "username" => 'test_username',
                "password" => bcrypt('123123123'),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
