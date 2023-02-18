<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('image_url')->nullable();
            $table->text('category_name')->nullable();
            $table->integer('status');
            $table->timestamps();
        });

        Category::create(
            [
                "category_name" => "Meal",
                "status" => 1,
                "image_url" => ""
            ]
        );
        Category::create(
            [
                "category_name" => "Snack",
                "status" => 1,
                "image_url" => ""
            ]
        );
        Category::create(
            [
                "category_name" => "Burger",
                "status" => 1,
                "image_url" => ""
            ]
        );
        Category::create(
            [
                "category_name" => "Fries",
                "status" => 1,
                "image_url" => ""
            ]
        );
        Category::create(
            [
                "category_name" => "Chicken",
                "status" => 1,
                "image_url" => ""
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
        Schema::dropIfExists('categories');
    }
}
