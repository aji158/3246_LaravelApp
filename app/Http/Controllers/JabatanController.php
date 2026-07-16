<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('jabatan.index', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100'
        ]);

        Jabatan::create([
            'name' => $request->name,
            'created_by' => 'Sistem/UAS'
        ]);

        return redirect()->back()->with('success', 'Data Jabatan Berhasil Ditambahkan!');
    }

    /**
     * Menampilkan halaman form edit jabatan
     */
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('jabatan.edit', compact('jabatan'));
    }

    /**
     * Memproses pembaruan data jabatan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100'
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Data Jabatan Berhasil Diperbarui!');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->back()->with('success', 'Data Jabatan Berhasil Dihapus!');
    }
}