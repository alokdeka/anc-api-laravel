<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // GNM, ANM, LHV, DPN
            $table->string('name');
            $table->string('duration'); // e.g. "3.5 Years", "2 Years"
            $table->text('eligibility')->nullable();
            $table->integer('seats')->default(0);
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
