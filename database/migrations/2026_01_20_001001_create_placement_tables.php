<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('placement_quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('time_limit')->default(15)->comment('In minutes');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('placement_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('placement_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_question_id')->constrained()->cascadeOnDelete();
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_options');
        Schema::dropIfExists('placement_questions');
        Schema::dropIfExists('placement_quizzes');
    }
};
