<?php

declare(strict_types=1);

/**
 * Laravel Categorizable Package by Ali Bayat.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;

class CreateCategoriesTables extends Migration
{
    public function up()
    {
        if(!Schema::hasTable("categories"))
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); 
            $table->text('description')->nullable();
		    $table->integer('sorting')->nullable();  
            $table->string('type')->default('default');
            $table->softDeletes();
            NestedSet::columns($table);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        if(!Schema::hasTable("categories_models"))
        Schema::create('categories_models', function (Blueprint $table) {
            $table->integer('category_id');
            $table->morphs('model');
        });
    }

    public function down()
    {
        if(Schema::hasTable('categories'))
        Schema::dropIfExists('categories');
        
        if(Schema::hasTable('categories_models'))
        Schema::dropIfExists('categories_models');
    
    }
}
