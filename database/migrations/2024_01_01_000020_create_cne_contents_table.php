<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cne_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section')->unique(); // landing, eligibility, how_to_apply
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cne_contents');
    }
};
