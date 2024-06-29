@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品一覧画面</h1>
    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" class="form-control" placeholder="検索キーワード">
        </div>
        <div class="col-md-5">
            <select class="form-control">
                <option value="">メーカー名</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">検索</button>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-success">新規作成</a>
    </div>

    <table class="table table-bordered table-striped w-100">
        <thead>
            <tr>
                <th class="align-middle" style="width:5%;">ID</th>
                <th class="align-middle" style="width:15%;">商品画像</th>
                <th class="align-middle" style="width:20%;">商品名</th>
                <th class="align-middle" style="width:10%;">価格</th>
                <th class="align-middle" style="width:10%;">在庫数</th>
                <th class="align-middle" style="width:20%;">メーカー名</th>
                <th class="align-middle" style="width:20%;">アクション</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="align-middle" style="width:5%;">{{ $product->id }}</td>
                <td class="align-middle" style="width:15%;">
                    <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->product_name }}" class="img-thumbnail" style="max-width: 100px;">
                </td>
                <td class="align-middle" style="width:20%;">{{ $product->product_name }}</td>
                <td class="align-middle" style="width:10%;">{{ $product->price }}</td>
                <td class="align-middle" style="width:10%;">{{ $product->stock }}</td>
                <td class="align-middle" style="width:20%;">{{ $product->company->company_name }}</td>
                <td class="align-middle" style="width:20%;">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">詳細</a>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">編集</a> <!-- ここが新規追加部分 -->
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
