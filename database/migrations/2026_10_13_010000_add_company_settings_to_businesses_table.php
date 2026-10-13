<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('business_type')->nullable()->after('nit_ruc');
            $table->string('receipt_series', 20)->nullable()->after('business_type');
            $table->string('system_icon')->nullable()->after('logo');
            $table->string('mobile', 30)->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            $table->string('district')->nullable()->after('address');
            $table->string('province')->nullable()->after('district');
            $table->string('department')->nullable()->after('province');
            $table->string('country')->nullable()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'business_type',
                'receipt_series',
                'system_icon',
                'mobile',
                'website',
                'district',
                'province',
                'department',
                'country',
            ]);
        });
    }
};
