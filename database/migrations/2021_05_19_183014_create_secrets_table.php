<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecretsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('secrets');

        Schema::create('secrets', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 32)->unique('UX_secrets_slug')->index('IX_secrets_slug');
            $table->text('secret');
            $table->datetime('created_at');
            $table->datetime('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secrets');
    }
}
