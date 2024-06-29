@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品一覧画面</h1>
    <form action="{{ route('products.search') }}" method="POST" class="mb-3">
        @csrf
        <div class="row">
            <div class="col-md-5">
                <input type="text" name="product_name" class="form-control" placeholder="商品名で検索">
            </div>
            <div class="col-md-5">
                <select name="company_id" class="form-control">
                    <option value="">メーカー名で検索</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </div>
    </form>

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
                <th class="align-middle">ID</th>
                <th class="align-middle">商品画像</th>
                <th class="align-middle">商品名</th>
                <th class="align-middle">価格</th>
                <th class="align-middle">在庫数</th>
                <th class="align-middle">メーカー名</th>
                <th class="align-middle">アクション</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="align-middle">{{ $product->id }}</td>
                <td class="align-middle"><img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->product_name }}" class="img-thumbnail" style="max-width: 100px;"></td>
                <td class="align-middle">{{ $product->product_name }}</td>
                <td class="align-middle">{{ $product->price }}</td>
                <td class="align-middle">{{ $product->stock }}</td>
                <td class="align-middle">{{ $product->company->company_name }}</td>
                <td class="align-middle">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">詳細</a>
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
        {{ $products->appends(request()->input())->links() }}
    </div>
</div>
@endsection
