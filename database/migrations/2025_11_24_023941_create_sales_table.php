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
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->decimal('probability', 5, 2)->default(0); // Win probability 0-100
            $table->string('color')->default('#3b82f6');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            $table->index(['pipeline_id', 'order']);
        });

        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pipeline_id')->constrained();
            $table->foreignId('stage_id')->constrained('pipeline_stages');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('value', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('owner_id')->constrained('users');
            $table->date('expected_close_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->string('status')->default('open'); // open, won, lost
            $table->string('lost_reason')->nullable();
            $table->decimal('probability', 5, 2)->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'owner_id']);
            $table->index(['tenant_id', 'expected_close_date']);
        });

        Schema::create('deal_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->string('type'); // note, call, email, meeting, stage_change
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['deal_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal_activities');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('pipeline_stages');
        Schema::dropIfExists('pipelines');
    }
};
