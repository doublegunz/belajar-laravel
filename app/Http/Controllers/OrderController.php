<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class OrderController extends Controller
{
    public function addOrder()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('orders.add', compact('products'));
    }

    public function getProduct($id)
    {
        $products = Product::findOrFail($id);
        return response()->json($products, 200);
    }

    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products, id',
            'qty' => 'required|integer'
        ]);

        //mengambil data product
        $product = Product::findOrFail($request->product_id);
        $getCart = json_decode($request->cookie('cart'), true);

        //jikala datanya ada
        if ($getCart) {
            //jika key ada
            if (array_key_exists($request->product_id, $getCart)) {
                //jumlahkan qty barangnya
                $getCart[$request->product_id]['qty'] += $request->qty;

                //dikirim kembali untuk disimpan ke cookie
                return response()->json($getCart, 200)
                    ->cookie('cart', json_encode($getCart), 120);
            }
        }

        $getCart[$request->product_id] = [
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $request->qty
        ];

        //kirim responnya, lalu simpan ke cookie
        return response()->json($getCart, 200)
            ->cookie('cart', json_decode($getCart), 120);
    }

    public function getCart()
    {
        //mengambil cart dari cookie
        $cart = json_decode(request()->cookie('cart'), true);
        //mengirim kembali dalam bentuk json untuk ditampilkan dengan vuejs
    }

    public function removeCart($id)
    {
        $cart = json_decode(request()->cookie('cart'), true);
        //hapus cart berdasarkna product_id
        unset($cart[$id]);

        //cart diperbaharui
        return response()->json($cart, 200)->cookie('cart', json_encode($cart), 120);
    }

    public function checkout()
    {
        return view('orders.checkout');
    }
}
