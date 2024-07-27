<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function getToken() {
        return csrf_token();
    }

    public function purchase(Request $request)
    {

        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');

        if($product_id == null || $quantity == null) {
            return response()->json(['error' => 'パラメーターが不足しています。'], 401);
        }

        DB::beginTransaction();

        try {
            // 商品の在庫確認
            $product = Product::lockForUpdate()->find($product_id);

            if ($product->stock < $quantity) {
                return response()->json(['error' => '在庫が不足しています。'], 400);
            }

            // salesテーブルにレコードを追加
            $sale = new Sale();
            $sale->product_id = $product_id;
            $sale->save();

            // productsテーブルの在庫数を減算
            $product->stock -= $quantity;
            $product->save();

            DB::commit();

            return response()->json(['message' => '購入が完了しました。'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e], 500);
        }
    }
}
