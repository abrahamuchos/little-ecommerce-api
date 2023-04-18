<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display all products to guest users
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Product::where('is_available', true)->get();

        return ProductResource::collection($products->load('category'));
    }

    /**
     * Get all products to clients and suppliers
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function allProducts(): AnonymousResourceCollection
    {
        $user = Auth::user();
        $this->authorize('viewAny', [Product::class, $user]);

        if ($user->roles()->first()->id === User::IS_CLIENT) {
            $products = Product::where('is_available', true)->get();
        } else {
            //Supplier user
            $products = Product::whereHas('users', function (Builder $query) use ($user) {
                return $query->where('id', '=', $user->id);
            })->get();
        }

        return ProductResource::collection($products->load('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     *
     * @return ProductResource|JsonResponse
     * @throws AuthorizationException
     */
    public function store(ProductRequest $request): ProductResource|JsonResponse
    {
        $this->authorize('create', Product::class);
        $user = Auth::user();

        try {
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'brand' => $request->brand,
                'sku' => $request->sku,
                'price' => $request->price,
                'is_available' => (bool)$request->is_available,
            ]);

            $product->users()->attach($user);

        } catch (QueryException|\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return new ProductResource($product->load('category'));
    }

    /**
     * Display the product. Authorize if is client or supplier
     *
     * @param Product $id
     *
     * @return ProductResource
     * @throws AuthorizationException
     */
    public function show(Product $id): ProductResource
    {
        $this->authorize('view', $id);

        return new ProductResource($id->load('category'));
    }

    /**
     * Show products by categories
     * @param Category $category
     *
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function showByCategory(Category $category): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Product::class);
        $userRole = Auth::user()->roles()->first()->id;

        if ($userRole === User::IS_CLIENT) {
            $products = Product::whereHas('category', function (Builder $query) use ($category) {
                return $query->where('id', '=', $category->id);
            })->get();
        } else {
            // Supplier user
            $user = Auth::user();
            $products = Product::whereHas('users', function (Builder $query) use ($user) {
                return $query->where('id', '=', $user->id);
            })->whereHas('category', function (Builder $query) use ($category) {
                return $query->where('id', '=', $category->id);
            })->get();
        }

        return ProductResource::collection($products->load('category'));
    }


    /**
     * Update the specified product in storage.
     *
     * @param ProductRequest $request
     * @param Product        $product
     *
     * @return JsonResponse|ProductResource
     * @throws AuthorizationException
     */
    public function update(ProductRequest $request, Product $product): JsonResponse|ProductResource
    {
        $this->authorize('update', $product);

        try {
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'brand' => $request->brand,
                'sku' => $request->sku,
                'price' => $request->price,
                'is_available' => $request->is_available,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }

        return new ProductResource($product->load('category'));
    }

    /**
     * Change to availability to product
     *
     * @param Request $request
     * @param Product $product
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function available(Request $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        try {
            $product->update([
                'is_available' => $request->is_available,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Your availability was updated to ' . $product->name
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return response()->json([
            'message' => 'Delete a product'
        ]);
    }
}
