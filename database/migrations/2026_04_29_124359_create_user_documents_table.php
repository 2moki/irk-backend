<?php

declare(strict_types=1);

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('document_type', DocumentType::cases())->index();
            $table->string('file_path');
            $table->string('file_name');
            $table->enum('document_status', DocumentStatus::cases())->default('pending')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
