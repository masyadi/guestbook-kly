<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent')->index('parent')->default(0);
            $table->string('name', 75)->index('name');
            $table->string('slug', 75)->index('slug');
            $table->string('icon', 25)->nullable()->default('fa fa-circle-o');
            $table->integer('order')->nullable()->default(0);
            $table->enum('status', ['0', '1'])->index('status')->comment('0:not active, 1:active')->default(1);
            $table->enum('show_all', ['0', '1'])->index('show_all')->comment('0:per role, 1:all role')->default(0);
            $table->string('description', 255)->nullable();
            $table->integer('created_by')->nullable()->index('created_by');
            $table->integer('updated_by')->nullable()->index('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
