<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // chatbot_conversations
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('guest_name', 100)->nullable();
            $table->string('guest_email', 255)->nullable();
            $table->enum('mode', ['bot', 'admin'])->default('bot');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('session_id');
            $table->index('user_id');
            $table->index('status');
        });

        // chatbot_messages
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chatbot_conversations')->onDelete('cascade');
            $table->enum('sender_type', ['user', 'bot', 'admin']);
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index('conversation_id');
            $table->index(['conversation_id', 'is_read']);
        });

        // chatbot_faqs
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question', 500);
            $table->text('answer');
            $table->string('category', 100)->default('general');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('category');
            $table->index('is_active');
        });

        // chatbot_quick_links
        Schema::create('chatbot_quick_links', function (Blueprint $table) {
            $table->id();
            $table->string('label', 200);
            $table->string('url_path', 500)->comment('Relative path e.g. /patient/doctors');
            $table->string('icon', 100)->default('fas fa-link');
            $table->json('roles')->nullable()->comment('NULL=all, or ["patient","doctor"]');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_quick_links');
        Schema::dropIfExists('chatbot_faqs');
        Schema::dropIfExists('chatbot_messages');
        Schema::dropIfExists('chatbot_conversations');
    }
};
