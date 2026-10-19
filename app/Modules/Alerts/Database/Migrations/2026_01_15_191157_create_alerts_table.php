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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();

            $table->string('type'); // uptime, ssl, http_error, etc
            $table->enum('severity', ['info', 'warning', 'critical'])->default('info');

            $table->string('title');
            $table->string('fingerprint')->index();

            $table->enum('status', ['open', 'acknowledged', 'resolved'])->default('open');

            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');

            $table->unsignedInteger('occurrences')->default(1);

            $table->json('last_payload')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
