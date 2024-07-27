<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::lockForUpdate()->find($request->product_id);

            if ($product->stock < $request->quantity) {
                return response()->json(['error' => '在庫が不足しています。'], 400);
            }

            $product->stock -= $request->quantity;
            $product->save();

            Sale::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);

            DB::commit();

            return response()->json(['message' => '購入が完了しました。'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '購入処理に失敗しました。', 'message' => $e], 500);
        }
    }
}
