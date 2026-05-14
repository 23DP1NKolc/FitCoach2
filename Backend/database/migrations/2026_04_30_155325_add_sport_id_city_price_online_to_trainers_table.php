<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->foreignId('sport_id')->nullable()->constrained('sports')->nullOnDelete()->after('id');
            $table->string('city')->nullable()->after('description');
            $table->decimal('price_per_hour', 8, 2)->nullable()->after('city');
            $table->boolean('online')->default(true)->after('price_per_hour');
        });
    }

    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            // SQLite dažreiz prasa vispirms nomest foreign key
            $table->dropConstrainedForeignId('sport_id');
            $table->dropColumn(['city', 'price_per_hour', 'online']);
        });
    }
};
