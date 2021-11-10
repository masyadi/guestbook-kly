<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 75)->index('name');
            $table->string('username', 75)->index('username');
            $table->string('email')->unique()->comment('user email');
            $table->string('phone', 20)->nullable()->index();
            $table->integer('role_id')->index('role_id')->default(1)->nullable();
            
            $table->string('photo')->nullable();

            $table->string('password')->nullable();
            $table->enum('status', ['-1', '0', '1'])->default('1')->index('status')->comment('-1: deleted, 0:not active, 1:active');
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('created_by')->nullable()->index('created_by');
            $table->integer('updated_by')->nullable()->index('updated_by');
            $table->timestamp('logged_in_at')->nullable()->comment('last detected login');
            $table->timestamp('logged_out_at')->nullable()->comment('last detected logout');
            $table->string('ip_address', 45)->nullable()->comment('ip address from user access');
            $table->string('token')->index('token')->nullable()->comment('User token');
            $table->rememberToken();
            $table->timestamps();
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
