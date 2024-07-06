@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報登録画面</h1>
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
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="product_name">商品名 <span class="text-danger">*</span></label>
            <input type="text" name="product_name" class="form-control" id="product_name" value="{{ old('product_name') }}">
        </div>
        <div class="form-group">
            <label for="company_id">メーカー名 <span class="text-danger">*</span></label>
            <select name="company_id" class="form-control" id="company_id">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price">価格 <span class="text-danger">*</span></label>
            <input type="number" name="price" class="form-control" id="price" value="{{ old('price') }}">
        </div>
        <div class="form-group">
            <label for="stock">在庫数 <span class="text-danger">*</span></label>
            <input type="number" name="stock" class="form-control" id="stock" value="{{ old('stock') }}" >
        </div>
        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea name="comment" class="form-control" id="comment">{{ old('comment') }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" class="form-control-file" id="image">
        </div>
        <button type="submit" class="btn btn-primary">新規登録</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
