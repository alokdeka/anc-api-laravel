<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('program_code'); // GNM, ANM, etc.
            $table->date('exam_date');
            $table->time('exam_time')->nullable();
            $table->string('center')->nullable();
            $table->string('year')->nullable();
            $table->string('semester')->nullable();
            $table->longText('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
