<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use Cloudinary\Cloudinary;

class PartnerController extends Controller
{
    /**
     * Helper privat untuk inisialisasi Cloudinary SDK
     */
    private function getCloudinaryInstance()
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name') ?? env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => config('services.cloudinary.api_key') ?? env('CLOUDINARY_API_KEY'),
                'api_secret' => config('services.cloudinary.api_secret') ?? env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    public function index()
    {
        $partners = Partner::latest()->paginate(10);
        return view('admin.partners.index', compact('partners'));
    }
    
    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048'
        ]);

        // Upload ke Cloudinary jika ada file logo
        if ($request->hasFile('logo_url')) {
            $cloudinary = $this->getCloudinaryInstance();

            $uploadedFile = $cloudinary->uploadApi()->upload(
                $request->file('logo_url')->getRealPath(),
                [
                    'folder' => 'amikom_partner_logos'
                ]
            );

            // Simpan URL HTTPS permanen ke kolom 'logo_url'
            $data['logo_url'] = $uploadedFile['secure_url'];
        }

        Partner::create($data);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan ke Cloudinary!');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
{
    // 1. Validasi Input
    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
    ]);

    // 2. Jika ada file logo baru yang diunggah
    if ($request->hasFile('logo_url')) {
        $cloudinary = $this->getCloudinaryInstance();

        $uploadedFile = $cloudinary->uploadApi()->upload(
            $request->file('logo_url')->getRealPath(),
            [
                'folder' => 'amikom_partner_logos'
            ]
        );

        // Simpan URL Cloudinary ke data yang akan di-update
        $data['logo_url'] = $uploadedFile['secure_url'];
    }

    // 3. Update data partner di MySQL
    $partner->update($data);

    return redirect()
        ->route('admin.partners.index')
        ->with('success', 'Partner berhasil diupdate!');
}

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus');
    }
}