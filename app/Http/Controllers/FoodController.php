<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    use ResponseApi;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods = Food::select('id', 'name', 'photo', 'description', 'price')
            ->limit(30)
            ->orderBy('id', 'DESC')
            ->get();

        return $this->successResponse($foods);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        return $food;
    }
}
