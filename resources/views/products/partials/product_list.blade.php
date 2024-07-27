<table class="table table-bordered table-striped w-100">
    <thead>
        <tr>
            <th class="align-middle"><a href="#" class="sort" data-sort="id">ID</a></th>
            <th class="align-middle">商品画像</th>
            <th class="align-middle"><a href="#" class="sort" data-sort="product_name">商品名</a></th>
            <th class="align-middle"><a href="#" class="sort" data-sort="price">価格</a></th>
            <th class="align-middle"><a href="#" class="sort" data-sort="stock">在庫数</a></th>
            <th class="align-middle"><a href="#" class="sort" data-sort="company_name">メーカー名</a></th>
            <th class="align-middle">アクション</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr id="product-row-{{ $product->id }}">
            <td class="align-middle">{{ $product->id }}</td>
            <td class="align-middle">
                @if ($product->img_path)
                    <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->product_name }}" class="img-thumbnail" style="max-width: 100px;">
                @else
                    画像なし
                @endif
            </td>
            <td class="align-middle">{{ $product->product_name }}</td>
            <td class="align-middle">{{ $product->price }}</td>
            <td class="align-middle">{{ $product->stock }}</td>
            <td class="align-middle">{{ $product->company->company_name }}</td>
            <td class="align-middle">
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info">詳細</a>
                <button type="button" class="btn btn-danger delete-btn" data-product-id="{{ $product->id }}">削除</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $products->appends(request()->input())->links() }}
</div>
