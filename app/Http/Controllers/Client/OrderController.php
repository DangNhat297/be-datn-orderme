<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Dishes;
use App\Models\Order;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected Order $order,
        protected Dishes $dish,
        protected Cart $cart,
        protected UserService $userService
    ) {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->check()) {
            $orders = $this->order
                ->newQuery()
                ->where('user_id', auth()->id)
                ->latest()
                ->get();

            return $this->sendSuccess($orders);
        }

        $phone = $this->userService->getInfoGuest();

        $orders = $this->order
            ->newQuery()
            ->where('phone', $phone)
            ->latest()
            ->get();

        return $this->sendSuccess($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'phone',
            'note',
            'location_id',
            'total'
        ]);

        $dishIDs = collect($request->dishes)->pluck('dish_id')->toArray();
        
        $dishes = $this->dish
            ->newQuery()
            ->findMany($dishIDs);

        $dishOfOrder = collect($request->dishes)->map(function ($dish) use ($dishes) {
            $dish['price'] = $dishes->find($dish['dish_id'])->price;

            return $dish;
        })->keyBy('dish_id');

        // $data['total'] = $dishOfOrder->reduce(function ($sum, $currentVal) {
        //     return $sum += $currentVal['price'];
        // }, 0);

        $res = DB::transaction(function () use ($data, $dishOfOrder) {
            $order = $this->order
                ->newQuery()
                ->create($data);

            $order->dishes()->attach($dishOfOrder);

            return $order;
        });

        return $res;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order->load('dishes');

        $order->dishes->transform(function ($dish) {
            $dish->quantity = $dish->pivot->quantity;
            $dish->price = $dish->pivot->price;

            return $dish->only([
                'id',
                'name',
                'slug',
                'image',
                'price',
                'quantity'
            ]);
        });

        return $this->sendSuccess($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->update(['status' => 0]);

        return $this->updateSuccess($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
