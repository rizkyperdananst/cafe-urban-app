<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Tampilkan daftar semua menu.
     */
    public function index()
    {
        $menus = Menu::with('category')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Form tambah menu baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Simpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'nullable|integer|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        // Default status tersedia
        $validated['is_available'] = $request->has('is_available');

        Menu::create($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan ✅');
    }

    /**
     * Form edit menu.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update data menu yang sudah ada.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'nullable|integer|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        // Jika admin upload gambar baru
        if ($request->hasFile('image')) {
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        $menu->update($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui ✨');
    }

    /**
     * Hapus menu.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus 🗑️');
    }
}
