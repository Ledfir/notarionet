<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('access')->default(0);

            $table->string('rfc')->nullable();
            $table->string('curp')->nullable();
            $table->string('clave_ine')->nullable();
            $table->unsignedBigInteger('rfc_documents_id')->nullable();
            $table->unsignedBigInteger('curp_documents_id')->nullable();
            $table->unsignedBigInteger('inefront_documents_id')->nullable();
            $table->unsignedBigInteger('ineback_documents_id')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedBigInteger('signature_image_id')->nullable();
            

            $table->timestamps();
            $table->softDeletes();
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
