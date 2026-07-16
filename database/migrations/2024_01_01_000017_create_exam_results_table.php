<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examination_id');
            $table->string('roll_number');
            $table->string('candidate_name');
            $table->string('registration_number')->nullable();
            $table->decimal('marks', 6, 2)->nullable();
            $table->decimal('total_marks', 6, 2)->nullable();
            $table->string('percentage')->nullable();
            $table->enum('result', ['pass', 'fail', 'absent', 'withheld'])->default('pass');
            $table->integer('rank')->nullable();
            $table->string('grade')->nullable();
            $table->timestamps();

            $table->foreign('examination_id')->references('id')->on('examinations')->cascadeOnDelete();
            $table->index(['examination_id', 'roll_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
