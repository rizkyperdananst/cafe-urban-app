<?php

namespace App\Http\Controllers\Customer;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('category')->where('is_available', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $menus = $query->paginate(8);
        $categories = Category::all();

        return view('customer.menu', compact('menus', 'categories'));
    }
}
