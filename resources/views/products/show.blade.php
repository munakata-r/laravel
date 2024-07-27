@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品詳細</h1>
    <div class="card">
        <div class="card-header">{{ $product->product_name }}</div>
        <div class="card-body">
            <p>ID: {{ $product->id }}</p> <!-- 商品IDの表示を追加 -->
            <p>価格: {{ $product->price }}円</p>
            <p>在庫数: {{ $product->stock }}</p>
            <p>メーカー名: {{ $product->company->company_name }}</p>
            @if ($product->img_path)
                <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->product_name }}" class="img-thumbnail" style="max-width: 300px;">
            @else
                <p>画像なし</p>
            @endif
            <hr>
            <form id="purchase-form">
                @csrf
                <div class="form-group">
                    <label for="quantity">購入数量</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                </div>
                <button type="button" class="btn btn-success purchase-btn" data-product-id="{{ $product->id }}">購入</button>
            </form>
            <hr>
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">編集</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </div>
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

        $(document).on('click', '.purchase-btn', function(e) {
            e.preventDefault();

            var productId = $(this).data('product-id');
            var quantity = $('#quantity').val();

            $.ajax({
                url: "{{ route('purchase') }}",
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    console.log('購入が完了しました:', response);
                    alert('購入が完了しました。');
                    location.reload(); // ページをリロードして在庫を更新
                },
                error: function(xhr) {
                    console.error('購入に失敗しました:', xhr);
                    alert('購入に失敗しました: ' + xhr.responseJSON.error);
                }
            });
        });
    });
</script>
@endpush
