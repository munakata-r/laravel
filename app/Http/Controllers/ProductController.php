<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }

        $products = $query->with('company')->paginate(10);
        $companies = Company::all();

        if ($request->ajax()) {
            return view('products.partials.product_list', compact('products'))->render();
        }

        return view('products.index', compact('products', 'companies'));
    }

    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'company_id' => 'required|exists:companies,id',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product;
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->company_id = $request->company_id;

        if ($request->hasFile('img_path')) {
            $path = $request->file('img_path')->store('public/images');
            $product->img_path = str_replace('public/', '', $path);
        }

        $product->save();

        return redirect()->route('products.index')->with('success', '商品が作成されました');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'company_id' => 'required|exists:companies,id',
        ]);

        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->company_id = $request->company_id;

        if ($request->hasFile('img_path')) {
            $path = $request->file('img_path')->store('public/images');
            $product->img_path = str_replace('public/', '', $path);
        }

        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', '商品情報が更新されました');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(['message' => '削除が完了しました。'], 200);
        } catch (\Exception $e) {
            \Log::error('削除に失敗しました。', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => '削除に失敗しました。'], 500);
        }
    }
}
