<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('events');

        // fitur search
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->oldest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255|unique:categories,name',
        ], [
            'name.unique' => 'Kategori sudah ada.'
        ]);

        $data['slug'] = Str::slug($request->name);

        // cek slug juga
        if (Category::where('slug', $data['slug'])->exists()) {
            return back()
                ->withErrors(['name' => 'Kategori sudah ada.'])
                ->withInput();
        }

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ]
        ], [
            'name.unique' => 'Kategori sudah ada',
            'name.required' => 'Nama kategori wajib diisi',
        ]);

        $data['slug'] = Str::slug($request->name);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
