<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Daftar produk (filter opsional: ?category_id=)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')->where('is_available', true);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('name')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar produk',
            'data'    => ProductResource::collection($products),
        ]);
    }

    /**
     * Detail satu produk
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return response()->json([
            'status'  => true,
            'message' => 'Detail produk',
            'data'    => new ProductResource($product),
        ]);
    }
}
