<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('parent')->default('0')->index('parent');
            $table->string('title', 125)->index('title');
            $table->string('event', 75)->index('event')->nullable();
            $table->timestamp('date')->index('date')->comment('event date')->nullable();
            $table->string('location', 255)->index('location')->nullable();
            $table->string('copyright', 75)->index('copyright')->nullable();
            $table->string('photographer', 75)->index('photographer')->nullable();
            $table->string('caption', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->integer('width')->index('width')->nullable();
            $table->integer('height')->index('height')->nullable();
            $table->string('ratio', 10)->nullable();
            $table->string('mime_type', 255)->index('mime_type')->comment('dir: directory');
            $table->integer('file_size')->index('file_size')->nullable();
            $table->string('path')->nullable();
            $table->string('exif_lat', 25)->index('exif_lat')->nullable();
            $table->string('exif_lng', 25)->index('exif_lng')->nullable();
            $table->string('exif_camera', 75)->index('exif_camera')->nullable();
            $table->string('exif_software', 255)->nullable();
            $table->timestamp('exif_date_taken')->index('exif_date_taken')->nullable();
            $table->enum('status', ['-1', '0', '1'])->default('1')->index('status')->comment('-1: deleted, 0:not active, 1:active');
            $table->enum('starred', ['0', '1'])->default('0')->index('starred')->comment('0:no, 1:yes');
            $table->integer('created_by')->index('created_by')->nullable();
            $table->integer('updated_by')->index('updated_by')->nullable();
            $table->text('rev')->nullable()->comment('JSON image revision');
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
        Schema::dropIfExists('image_banks');
    }
}
