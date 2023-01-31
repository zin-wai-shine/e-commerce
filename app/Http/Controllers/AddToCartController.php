<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddToCartRequest;
use App\Http\Requests\UpdateAddToCartRequest;
use App\Http\Resources\AddToCartResouce;
use App\Models\AddToCart;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

// When user click add to cart --> (store) --> when user reduce or increase product count on their add to cart--> (update)
// When user click delete --> delete();
// required --> user_id, product_id, count


class AddToCartController extends Controller
{
    public function index()
    {
        $addToCarts = AddToCart::where('user_id',Auth::id())->latest('id')->paginate(10)->withQueryString();
        return response()->json(['success' => true, 'data'=>AddToCartResouce::collection($addToCarts)]);
    }


    public function store(StoreAddToCartRequest $request)
    {
        $addToCart = new AddToCart();
        $addToCart->user_id = Auth::id();
        $addToCart->product_id = $request->product_id;
        $addToCart->count = $request->count;
        $addToCart->save();

        return response()->json(['success' => true, 'message' => 'added to cart', 'data'=>new AddToCartResouce($addToCart)]);
    }

    public function show($id)
    {
        $addToCart = AddToCart::find($id);
        if(is_null($addToCart)){
            return response()->json(['message'=>'cart not found']);
        }
        return response()->json(['success' => true, 'data' => new AddToCartResouce($addToCart)]);
    }



    public function update(UpdateAddToCartRequest $request, $id)
    {
        $addToCart = AddToCart::find($id);

        if(is_null($addToCart)){
            return response()->json(['message'=>'cart not found']);
        }

        $addToCart->count = $request->count;
        $addToCart->update();

        return response()->json(['success' => true, 'message' => 'count was updated', 'data' => new AddToCartResouce($addToCart)]);
    }

    public function destroy($id)
    {
        $addToCart = AddToCart::find($id);

        if(is_null($addToCart)){
            return response()->json(['message'=>'cart not found']);
        }
        $addToCart->delete();
        return response()->json(['success' => true, 'message' => 'removed add to cart'], 200);
    }
}
