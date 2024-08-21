@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品一覧画面</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規作成</a> <!-- 新規作成ボタンを追加 -->
    <form id="search-form" class="mb-3">
        @csrf
        <div class="row mb-2">
            <div class="col-md-4">
                <input type="text" name="product_name" class="form-control" placeholder="商品名で検索" id="product_name">
            </div>
            <div class="col-md-4">
                <select name="company_id" class="form-control" id="company_id">
                    <option value="">メーカー名で検索</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-2">
                <input type="number" name="price_min" class="form-control" placeholder="価格 下限" id="price_min">
            </div>
            <div class="col-md-2">
                <input type="number" name="price_max" class="form-control" placeholder="価格 上限" id="price_max">
            </div>
            <div class="col-md-2">
                <input type="number" name="stock_min" class="form-control" placeholder="在庫数 下限" id="stock_min">
            </div>
            <div class="col-md-2">
                <input type="number" name="stock_max" class="form-control" placeholder="在庫数 上限" id="stock_max">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </div>
    </form>

    <div id="product-list">
        @include('products.partials.product_list', ['products' => $products])
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 検索フォームの送信イベント
        $('#search-form').on('submit', function(e) {
            e.preventDefault();

            const productName = $('#product_name').val();
            const companyId = $('#company_id').val();
            const priceMin = $('#price_min').val();
            const priceMax = $('#price_max').val();
            const stockMin = $('#stock_min').val();
            const stockMax = $('#stock_max').val();
            const sort = $('#sort').val(); // ソートパラメータ
            const order = $('#order').val(); // ソート順序

            const params = {
                product_name: productName,
                company_id: companyId,
                price_min: priceMin,
                price_max: priceMax,
                stock_min: stockMin,
                stock_max: stockMax,
                sort: sort, // ソートパラメータを追加
                order: order, // ソート順序を追加
            };

            $.ajax({
                url: "{{ route('products.index') }}",
                method: 'GET',
                data: params,
                success: function(response) {
                    $('#product-list').html(response);
                },
                error: function(xhr) {
                    console.error('検索に失敗しました:', xhr);
                    alert('検索に失敗しました。');
                }
            });
        });

        // ソートリンクのクリックイベント
        $(document).on('click', '.sort', function(e) {
            e.preventDefault();

            const sort = $(this).data('sort'); // クリックされたリンクのソートパラメータを取得
            const currentOrder = $(this).data('order'); // 現在のソート順序を取得
            const newOrder = currentOrder === 'asc' ? 'desc' : 'asc'; // ソート順序を切り替え

            $(this).data('order', newOrder); // 新しいソート順序を設定

            $('#sort').val(sort); // フォームの隠しフィールドにソートパラメータを設定
            $('#order').val(newOrder); // フォームの隠しフィールドにソート順序を設定

            $('#search-form').submit(); // フォームを送信して検索をトリガー
        });

        /$(document).ready(function() {
        // 削除ボタンがクリックされたときの処理
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();

            // 削除する商品のIDを取得
            var productId = $(this).data('product-id');

            // 確認ダイアログ
            if (!confirm('本当に削除しますか？')) {
                return;
            }

            // Ajaxによる削除リクエスト
            $.ajax({
                url: `/products/${productId}`, // 削除対象のURL
                method: 'POST', // POSTメソッドを使用
                data: {
                    _token: '{{ csrf_token() }}', // CSRFトークン
                    _method: 'DELETE' // 実際にはDELETEメソッドとして処理
                },
                success: function(response) {
                    // 成功した場合、対象行を削除
                    $(`#product-row-${productId}`).remove();
                    alert('削除が完了しました。');
                },
                error: function(xhr) {
                    alert('削除に失敗しました: ' + xhr.responseText);
                }
            });
        });
    });
</script>
