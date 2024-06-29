<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('company')->paginate(10);
        $companies = Company::all();
        return view('home', compact('products', 'companies'));
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $products = $query->with('company')->get();
        $companies = Company::all();

        return view('home', compact('products', 'companies'));
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return redirect()->back()->with('status', 'Product deleted successfully');
        }

        return redirect()->back()->with('status', 'Product not found');
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'img_path' => 'nullable|image',
        ]);
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
    }

    public function show($id)
    {
        $product = Product::with('company')->find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('status', 'Product not found');
        }

        return view('products.show', compact('product'));
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'img_path' => 'nullable|image',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('product.index')->with('status', 'Product not found');
        }

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

        return redirect()->route('products.edit', $product->id)->with('status', 'Product updated successfully');
    }
}