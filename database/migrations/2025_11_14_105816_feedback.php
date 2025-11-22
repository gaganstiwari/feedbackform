<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.  **/
   public function up(): void
{
  Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('token_number')->nullable();
            $table->tinyInteger('nps_score')->nullable();
            $table->json('main_options')->nullable();
            $table->json('sub_options')->nullable();
            $table->text('comment')->nullable();
            $table->text('remark')->nullable();   // nullable as requested
            $table->string('status')->nullable();  // nullable as requested
            $table->softDeletes();
            $table->timestamps();
        });
}

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
