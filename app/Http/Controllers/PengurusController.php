<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class PengurusController extends Controller
{
    public function index()
    {
        // PERBAIKAN: Menggunakan get() agar relasi jabatan tidak eror
        $penguruses = Pengurus::with('jabatan')->get();
        $jabatans = Jabatan::all();
        return view('pengurus.index', compact('penguruses', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan_id' => 'required',
            'name' => 'required|max:100',
            'salary' => 'required|numeric',
        ]);

        Pengurus::create([
            'jabatan_id' => $request->jabatan_id,
            'name' => $request->name,
            'description' => $request->description,
            'salary' => $request->salary,
            'created_by' => 'Sistem/UAS'
        ]);

        return redirect()->back()->with('success', 'Data Pengurus Berhasil Ditambahkan!');
    }

    /**
     * Menampilkan halaman form edit pengurus
     */
    public function edit($id)
    {
        $pengurus = Pengurus::findOrFail($id);
        $jabatans = Jabatan::all(); // Dibutuhkan untuk memuat ulang daftar pilihan di dropdown select

        return view('pengurus.edit', compact('pengurus', 'jabatans'));
    }

    /**
     * Memproses pembaruan data pengurus
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan_id' => 'required',
            'name' => 'required|max:100',
            'salary' => 'required|numeric',
        ]);

        $pengurus = Pengurus::findOrFail($id);
        $pengurus->update([
            'jabatan_id' => $request->jabatan_id,
            'name' => $request->name,
            'description' => $request->description,
            'salary' => $request->salary,
        ]);

        return redirect()->route('admin.pengurus.index')->with('success', 'Data Pengurus Berhasil Diperbarui!');
    }

    public function destroy($id)
    {
        $pengurus = Pengurus::findOrFail($id);
        $pengurus->delete();

        return redirect()->back()->with('success', 'Data Pengurus Berhasil Dihapus!');
    }
}