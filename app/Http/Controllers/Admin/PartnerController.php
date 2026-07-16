<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;


class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::query();

        // fitur search
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $partners = $query->latest()->get();

        return view('admin.partners.index', compact('partners'));
    }
    public function create()
    {
        return view('admin.partners.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048'
        ]);

        if ($request->hasFile('logo_url')) {
            $data['logo_url'] = $request->file('logo_url')
                ->store('partners', 'public');
        }

        Partner::create($data);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan');
    }
    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }
    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'logo_url' => 'nullable|image'
        ]);

        if ($request->hasFile('logo_url')) {
            $data['logo_url'] = $request->file('logo_url')
                ->store('partners', 'public');
        }

        $partner->update($data);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil diupdate');
    }
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus');
    }
}
