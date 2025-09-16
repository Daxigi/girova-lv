<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Type;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $types = Type::all();

        return Inertia::render('Products/Create', [
            'categories' => $categories,
            'types' => $types,
        ]);
    }
}
