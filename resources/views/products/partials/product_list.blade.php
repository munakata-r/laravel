<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <!-- テーブルヘッダーの設定 -->
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
        <tr id="product-row-{{ $product->id }}"> <!-- 行にユニークIDを設定 -->
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

@push('scripts')
<script>
    $(document).ready(function() {
        // テーブルにTablesorterを適用
        $("#myTable").tablesorter({
            theme: 'default',
            sortList: [[0, 1]], // 初期表示でIDを降順でソート
            headers: {
                1: { sorter: false }, // 商品画像のカラム（2番目）をソート不可にする
                6: { sorter: false }  // アクションのカラム（7番目）をソート不可にする
            }
        });
    });
</script>
@endpush
