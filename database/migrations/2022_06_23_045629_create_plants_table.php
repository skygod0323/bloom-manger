<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->float('earliest_seed')->nullable();
            $table->float('latest_seed')->nullable();
            $table->float('harden')->nullable();
            $table->float('transplant')->nullable();
            $table->float('maturity')->nullable();
            $table->string('light')->nullable();
            $table->float('depth')->nullable();
            $table->string('seed_note')->nullable();
            $table->string('transplant_note')->nullable();
            $table->string('harvest_note')->nullable();
            $table->string('direct_sow')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('harvest')->nullable();
            $table->string('stagger')->nullable();
            $table->tinyInteger('has_task')->default(0);
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
        Schema::dropIfExists('plants');
    }
}
