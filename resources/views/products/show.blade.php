@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報詳細画面</h1>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">
            <h2>{{ $product->product_name }}</h2>
        </div>
        <div class="card-body">
            @if ($product->img_path)
                <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->product_name }}" class="img-thumbnail" style="max-width: 300px;">
            @endif
            <p><strong>商品ID:</strong> {{ $product->id}}</p>
            <p><strong>メーカー名:</strong> {{ $product->company->company_name }}</p>
            <p><strong>価格:</strong> ¥{{ $product->price }}</p>
            <p><strong>在庫数:</strong> {{ $product->stock }}</p>
            <p><strong>コメント:</strong> {{ $product->comment }}</p>
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">編集</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </div>
    </div>
</div>
@endsection
