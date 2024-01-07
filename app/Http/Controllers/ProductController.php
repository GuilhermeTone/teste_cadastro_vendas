<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProdutos(Request $request)
    {
        
        $produtos = Product::all();
        return response()->json($produtos);
        
    }
}
