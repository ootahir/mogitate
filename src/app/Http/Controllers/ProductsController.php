<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\update;
use App\Models\Products;
use App\Models\Seasons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $productsQuery = Products::query();
        $isSearch = $request->filled('keyword');


        if ($isSearch) {
            $productsQuery->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->sort === 'high') {
            $productsQuery->orderBy('price', 'desc');
        } elseif ($request->sort === 'low') {
            $productsQuery->orderBy('price', 'asc');
        } else {
            $productsQuery->latest('id');
        }

        $products = $productsQuery->paginate(6)->appends($request->query());

        if ($isSearch) {
            return view('products.search', compact('products'));
        }

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $seasons = Seasons::orderBy('id')->get();

        return view('products.register', compact('seasons'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $imagePath = $request->file('image')->store('products', 'public');

        $product = Products::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $imagePath,
            'description' => $validated['description'],
        ]);

        $product->seasons()->attach($validated['season_ids']);

        return redirect('/products');
    }

    public function show($productId)
    {
        $product = Products::with('seasons')->findOrFail($productId);
        $seasons = Seasons::orderBy('id')->get();

        return view('products.detail', compact('product', 'seasons'));
    }

    public function edit($productId)
    {
        $product = Products::with('seasons')->findOrFail($productId);
        $seasons = Seasons::orderBy('id')->get();

        return view('products.update', compact('product', 'seasons'));
    }

    public function update(update $request, $productId)
    {
        $product = Products::with('seasons')->findOrFail($productId);
        $validated = $request->validated();

        // 新しい画像が提供された場合は処理
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $validated['image'] ?? $product->image,
            'description' => $validated['description'],
        ]);

        $product->seasons()->sync($validated['season_ids']);

        return redirect('/products');
    }

    public function destroy($productId)
    {
        $product = Products::findOrFail($productId);

        // 画像を削除
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect('/products');
    }
}
