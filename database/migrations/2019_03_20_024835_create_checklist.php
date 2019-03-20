<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("object_domain")->nullable();
            $table->string("object_id")->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_completed')->nullable();
            $table->string("updated_by")->nullable();
            $table->datetime("due")->nullable();
            $table->unsignedInteger("urgency")->nullable();
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
        Schema::dropIfExists('checklists');
    }
}
