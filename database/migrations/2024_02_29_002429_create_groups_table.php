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
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID of parent group.');
            $table->string('name_formal')->comment('Formal or long group name.');
            $table->string('name')->nullable()->comment('Common shorter group name.');
            $table->string('slug')->unique()->comment('URL slug for group. Default to lower-case name no spaces.');
            $table->string('description')->nullable()->comment('Group description.');
            $table->string('logo_url')->nullable()->comment('Optional logo image URL.');
            $table->boolean('is_active')->default(true)->comment('Must be active to be visible.');

            // created_at, updated_at
            $table->timestamps();

            // Self-referencing FK. parent_id is a foreign key to id to
            // allow parent/child groups (i.e. sub-groups).
            $table->foreign('parent_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't need to dropForeign first since the
        // FK points to this same table.
        Schema::dropIfExists('groups');
    }
};
