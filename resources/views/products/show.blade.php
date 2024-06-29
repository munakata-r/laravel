@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品詳細情報</h1>
    <div class="card">
        <div class="card-header">{{ $product->product_name }}</div>
        <div class="card-body">
            <p><strong>メーカー名:</strong> {{ $product->company->company_name }}</p>
            <p><strong>価格:</strong> ¥{{ $product->price }}</p>
            <p><strong>在庫数:</strong> {{ $product->stock }}</p>
            <p><strong>コメント:</strong> {{ $product->comment }}</p>
            @if ($product->image_path)
                <p><strong>商品画像:</strong></p>
                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="img-fluid">
            @endif
            <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">戻る</a>
        </div>
    </div>
</div>
@endsection
