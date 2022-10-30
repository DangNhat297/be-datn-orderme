<?php

    namespace App\Http\Controllers\Client;

    use App\Http\Controllers\Controller;
    use App\Models\Cart;
    use App\Models\CartProduct;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class CartController extends Controller
    {
        protected $cart;
        protected $cartProduct;

        public function __construct(Cart $cart, CartProduct $cartProduct)
        {
            $this->cart = $cart;
            $this->cartProduct = $cartProduct;
        }

//        show by user id
        public function show($id): JsonResponse
        {
            $data = $this->cart
                ->newQuery()
                ->where('user_id', $id)
                ->with(['cartDetail'])
                ->orderBy('created_at', 'DESC')
                ->get();
            return $this->sendSuccess($data);
        }

//        add new
        public function store(Request $request)
        {
            $cart = $this->cart->where('user_id', $request->user_id)->first();

            if ($cart != null) {
                $cartDetail = $this->cartProduct->where('dish_id', $request->dish_id)
                    ->where('cart_id', $cart->id)
                    ->first();

                if (!$cartDetail) {
                    $data = [
                        'dish_id' => (int)$request->dish_id,
                        'cart_id' => (int)$cart->id,
                        'quantity' => (int)$request->quantity
                    ];
                    $cartDetail = $this->cartProduct->addNewCartDetail($data);

                } else {
                    $data = [
                        'quantity' => (int)$cartDetail->quantity += (int)$request->quantity
                    ];
                    $cartDetail = $this->cartProduct->updateCartDetail($data, $cartDetail->dish_id);

                }

            } else {

                $cart = $this->cart->addNewCart($request->only('user_id'));

                $data = [
                    'dish_id' => (int)$request->dish_id,
                    'cart_id' => (int)$cart->id,
                    'quantity' => (int)$request->quantity
                ];

                $cartDetail = $this->cartProduct->addNewCartDetail($data);
            }

            return $this->createSuccess($cartDetail);
        }


//    update quantity
//   postman use raw ,
//  form-Data : use POST add overwrite _method PUT

        public function update(Request $request, $id): JsonResponse
        {
            $item = $this->cartProduct->newQuery()->findOrFail($id);
            $item->update([
                'quantity' => (int)$item->quantity += (int)$request->quantity
            ]);

            return $this->updateSuccess($item);
        }

//         delete

        public function destroy($id): JsonResponse
        {
            $data = $this->cartProduct->newQuery()
                ->findOrFail($id)
                ->delete();
            return $this->deleteSuccess();
        }



//    delete the selection [1,2,3]
//    use  raw
//    {
//    "dataSelection":[9,11]
//    }

// use ajax  new FormData() ,input checkbox dataSelection[] ok ,
//  use postman formData ['1','2','3'] error ,
// use postman raw  ok
        function Delete_Cart_By_Selection(Request $request):JsonResponse
        {
            $data = $request->dataSelection;
            $this->cartProduct->newQuery()->whereIn('id', $data)->delete();
            return $this->deleteSuccess();
        }


    }
