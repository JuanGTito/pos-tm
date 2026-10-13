<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BusinessSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_company_identity_contact_and_location(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        Role::create(['name' => 'admin']);
        $admin->assignRole('admin');

        Business::create([
            'name' => 'Tienda anterior',
            'nit_ruc' => '20111111111',
        ]);

        $response = $this->actingAs($admin)->put(route('business.update'), [
            'name' => 'Tecno Market',
            'nit_ruc' => '20987654321',
            'receipt_series' => 'B001',
            'business_type' => 'Venta de productos electrónicos',
            'phone' => '01 555 1020',
            'mobile' => '987654321',
            'email' => 'ventas@tecnomarket.pe',
            'website' => 'https://tecnomarket.pe',
            'address' => 'Av. Principal 123',
            'district' => 'Miraflores',
            'province' => 'Lima',
            'department' => 'Lima',
            'country' => 'Perú',
            'logo_file' => UploadedFile::fake()->create('logo.png', 100, 'image/png'),
            'system_icon_file' => UploadedFile::fake()->create('icon.png', 50, 'image/png'),
        ]);

        $response->assertRedirect(route('business.edit'));
        $response->assertSessionHas('success');

        $business = Business::firstOrFail();

        $this->assertSame('Tecno Market', $business->name);
        $this->assertSame('B001', $business->receipt_series);
        $this->assertSame('987654321', $business->mobile);
        $this->assertSame('Miraflores', $business->district);
        Storage::disk('public')->assertExists($business->logo);
        Storage::disk('public')->assertExists($business->system_icon);
    }

    public function test_non_admin_cannot_open_or_update_company_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('business.edit'))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('business.update'), [
                'name' => 'Cambio no permitido',
                'nit_ruc' => '20123456789',
            ])
            ->assertForbidden();
    }
}
