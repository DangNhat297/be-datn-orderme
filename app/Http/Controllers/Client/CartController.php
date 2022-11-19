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

    /**
     * @OA\Get(
     *      path="/client/cart/{id}",
     *      operationId="getCartByUserId",
     *      tags={"Cart"},
     *      summary="Get list of Cart",
     *      description="Returns list of Cart",
     *      security={{ "tokenJWT": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CartResponse")
     *       ),
     *     )
     */
    public function show($id): JsonResponse
    {
        $data = $this->cart
            ->newQuery()
            ->where('user_id', $id)
            ->with(['cartDetail'])
            ->orderBy('created_at', 'DESC')
            ->first();
        return $this->sendSuccess($data);
    }

    /**
     * @OA\Post(
     *      path="/client/cart",
     *      operationId="addToCart",
     *      tags={"Cart"},
     *      summary="Add products to cart",
     *      description="Returns cart data",
     *      security={{ "tokenJWT": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CartCreate")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CartResponse")
     *       ),
     * )
     */
    public function store(Request $request): JsonResponse
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
                    'dish_id' => (int)$cartDetail->dish_id,
                    'cart_id' => (int)$cart->id,
                    'quantity' => (int)$cartDetail->quantity += (int)$request->quantity
                ];
                $cartDetail = $this->cartProduct->updateCartDetail($data);


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

    /**
     * @OA\Put(
     *      path="/client/cart/{id}",
     *      operationId="updateCart",
     *      tags={"Cart"},
     *      summary="Update existing cart",
     *      description="Returns updated cart data",
     *      security={{ "tokenJWT": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Cart id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CartUpdate")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CartResponse")
     *       )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $item = $this->cartProduct->newQuery()->findOrFail($id);
        $item->update([
            'quantity' => $request->quantity
        ]);

        return $this->updateSuccess($item);
    }

    /**
     * @OA\Delete(
     *      path="/client/cart/{id}",
     *      operationId="deleteCart",
     *      tags={"Cart"},
     *      summary="Delete existing cart",
     *      description="Deletes a record and returns no content",
     *      security={{ "tokenJWT": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Cart id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $data = $this->cartProduct->newQuery()
            ->findOrFail($id)
            ->delete();
        return $this->deleteSuccess();
    }


    /**
     * @OA\Post (
     *      path="/client/cart/deleteMultiple",
     *      operationId="deleteCartMultiple",
     *      tags={"Cart"},
     *      summary="Delete existing cart",
     *      description="Delete multiple record and returns no content",
     *      security={{ "tokenJWT": {} }},
     *      @OA\Parameter(
     *          name="cartIds[]",
     *          description="Cart id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(type="array", @OA\Items(type="number")),
     *      ),
     *       @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       )
     * )
     */
    function Delete_Cart_By_Selection(Request $request): JsonResponse
    {
        $data = $request->cartIds;
        foreach ($data as $cart) {
            $this->cartProduct->newQuery()->findOrFail($cart)->delete();
        }
        return $this->deleteSuccess();
    }


}
