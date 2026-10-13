<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    public function edit()
    {
        $this->authorizeAdmin();

        $business = Business::query()->firstOrCreate([], [
            'name' => config('app.name', 'Mi negocio'),
            'nit_ruc' => 'PENDIENTE',
            'country' => 'Perú',
        ]);

        return view('business.edit', compact('business'));
    }

    public function update(Request $request)
    {
        $this->authorizeAdmin();

        $business = Business::query()->firstOrCreate([], [
            'name' => config('app.name', 'Mi negocio'),
            'nit_ruc' => 'PENDIENTE',
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nit_ruc' => ['required', 'string', 'max:30', Rule::unique('businesses', 'nit_ruc')->ignore($business)],
            'receipt_series' => ['nullable', 'string', 'max:20'],
            'business_type' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url:http,https', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'logo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'system_icon_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,ico', 'max:2048'],
        ]);

        if ($request->hasFile('logo_file')) {
            $this->replaceFile($business->logo);
            $validated['logo'] = $request->file('logo_file')->store('business', 'public');
        }

        if ($request->hasFile('system_icon_file')) {
            $this->replaceFile($business->system_icon);
            $validated['system_icon'] = $request->file('system_icon_file')->store('business', 'public');
        }

        unset($validated['logo_file'], $validated['system_icon_file']);
        $business->update($validated);

        return redirect()->route('business.edit')->with('success', 'La información de tu empresa fue actualizada.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
    }

    private function replaceFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
