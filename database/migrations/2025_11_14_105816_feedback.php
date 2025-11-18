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
        $table->unsignedTinyInteger('nps_score');

        $table->json('main_options')->nullable();
        $table->json('sub_options')->nullable();

        $table->text('comment')->nullable();
        $table->text('remark')->nullable();

        $table->timestamps();
        $table->softDeletes();
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
