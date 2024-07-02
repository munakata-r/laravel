<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 検索条件がある場合はクエリに追加
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 検索結果をページネーションで表示
        $products = $query->with('company')->paginate(10);
        $companies = Company::all();

        $request->flash();

        return view('home', compact('products', 'companies'));
    }

    public function show($id)
    {
        $product = Product::with('company')->find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('status', 'Product not found');
        }

        return view('products.show', compact('product'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $product = new Product();
            $product->product_name = $request->product_name;
            $product->company_id = $request->company_id;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->comment = $request->comment;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('public/images');
                $product->img_path = $path;
            }

            $product->save();

            return redirect()->route('products.index')->with('status', 'Product created successfully');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $companies = Company::all();

        if (!$product) {
            return redirect()->route('products.index')->with('status', 'Product not found');
        }

        return view('products.edit', compact('product', 'companies'));
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return redirect()->route('products.index')->with('status', 'Product not found');
            }

            $product->product_name = $request->product_name;
            $product->company_id = $request->company_id;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->comment = $request->comment;

            if ($request->hasFile('image')) {
                if ($product->img_path) {
                    Storage::delete($product->img_path);
                }
                $path = $request->file('image')->store('public/images');
                $product->img_path = $path;
            }

            $product->save();

            return redirect()->route('products.show', $product->id)->with('status', 'Product updated successfully');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return redirect()->route('products.index')->with('status', 'Product not found');
            }

            if ($product->img_path) {
                Storage::delete($product->img_path);
            }

            $product->delete();

            return redirect()->route('products.index')->with('status', 'Product deleted successfully');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}
