<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 20);
        if ($limit > 100) {
            $limit = 100;
        }
        $search = $request->query('search', null);
        $products = Product::latest('id')
            ->with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->paginate();
        // return response()->json($products);
        return response()->json(new ProductResource($products));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $product = Product::findOrFail($id);
            throw new RuntimeException('This is a test exception');
            return response()->json(new ProductDetailResource($product));
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Product not found'
                ], 404);
            } else {
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'An error occurred'
                ], 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
