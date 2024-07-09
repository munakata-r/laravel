@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報編集画面</h1>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_id">商品ID</label>
            <input type="text" name="product_id" class="form-control" id="product_id" value="{{ $product->id }}" readonly>
        </div>
        <div class="form-group">
            <label for="product_name">商品名 <span class="text-danger">*</span></label>
            <input type="text" name="product_name" class="form-control" id="product_name" value="{{ old('product_name', $product->product_name) }}" required>
        </div>
        <div class="form-group">
            <label for="company_id">メーカー名 <span class="text-danger">*</span></label>
            <select name="company_id" class="form-control" id="company_id" required>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $company->id == $product->company_id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">価格 <span class="text-danger">*</span></label>
            <input type="number" name="price" class="form-control" id="price" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="form-group">
            <label for="stock">在庫数 <span class="text-danger">*</span></label>
            <input type="number" name="stock" class="form-control" id="stock" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea name="comment" class="form-control" id="comment">{{ old('comment', $product->comment) }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" class="form-control-file" id="image">
            @if ($product->img_path)
                <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->product_name }}" class="img-thumbnail mt-2" style="max-width: 300px;">
            @endif
        </div>
        <button type="submit" class="btn btn-warning">更新</button>
        <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
