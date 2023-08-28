<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedures_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->default(null);
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('position');
            $table->timestamps();
        });

        Schema::create('procedures_procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('name');
            $table->text('body');
            $table->integer('position');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('procedures_categories')
                ->cascadeOnDelete();
        });

        Schema::create('procedures_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id');
            $table->string('name');
            $table->string('file_url');

            $table->foreign('procedure_id')
                ->references('id')
                ->on('procedures_procedures')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedures_categories');
        Schema::dropIfExists('procedures_procedures');
        Schema::dropIfExists('procedures_files_procedures');
    }
};
