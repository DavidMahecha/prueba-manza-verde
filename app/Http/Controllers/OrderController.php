<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Food;
use App\Models\Order;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ResponseApi;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::select('id', 'status')
            ->withCount('details')
            ->withSum('details', 'price')
            ->where('user_id', Auth::user()->id)
            ->get();

        return $this->successResponse($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'indications' => 'string',
            'details' => 'required|array|min:1',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.food_id' => 'required|exists:foods,id',
        ]);

        $order = new Order;
        $order->fill($request->all());
        $order->status = OrderStatus::Solicitado;
        $order->user_id = Auth::user()->id;
        $order->save();

        foreach($request->details as $detail) {
            $food = Food::find($detail['food_id']);

            $order->details()->create([
                'price' => $food->price,
                'quantity' => $detail['quantity'],
                'food_id' => $detail['food_id'],
            ]);
        }

        return $this->successResponse($order, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(mixed $order)
    {
        $order = Order::select('id', 'status', 'address', 'indications')
            ->with('details')
            ->where('user_id', Auth::user()->id)
            ->where('id', $order)
            ->firstOrFail();

        return $this->successResponse($order);
    }

    public function confirm(Order $order)
    {
        return $this->update($order, OrderStatus::Completado);
    }

    public function destroy(Order $order)
    {
        return $this->update($order, OrderStatus::Cancelado);
    }

    private function update(Order $order, OrderStatus $status)
    {
        // var_dump(
        //     ((int)$order->status !== OrderStatus::Solicitado),
        //     $order->status,
        //     (int)$order->status,
        //     OrderStatus::Solicitado,
        //     (int)OrderStatus::Solicitado->value,
        // );die();
        if((int)$order->status !== OrderStatus::Solicitado->value) {
            return $this->errorResponse(['No se puede realizar esta modificación'], Response::HTTP_BAD_REQUEST);
        }

        $order->status = $status;
        $order->save();

        return $this->successResponse($order);
    }
}
