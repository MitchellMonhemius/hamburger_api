<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\v1\BurgerService;

class BurgerController extends Controller
{
    protected $burgers;
    public function __construct(BurgerService $service)
    {
        $this->burgers = $service;

        $this->middleware('auth:api', ['only' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = request()->input();

        //call service
        $data = $this->burgers->getBurgers($parameters);

        return response()->json($data);

        //return data
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->burgers->validate($request->all());

        try
        {
            $burger = $this->burgers->createBurger($request);
            return response()->json($burger, 201);
        }
        catch (Exception $e)
        {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parameters = request()->input('id');

        //call service
        $data = $this->burgers->getBurgers($parameters);

        return response()->json($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->burgers->validate($request->all());

        try
        {
            $burger = $this->burgers->updateBurger($request, $id);
            return response()->json($burger, 200);
        }
        catch (ModelNotFoundException $ex)
        {
            throw $ex;
        }
        catch (Exception $e)
        {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $burger = $this->burgers->deleteBurger($id);
            return response()->make('', 204);
        }
        catch (ModelNotFoundException $ex)
        {
            throw $ex;
        }
        catch (Exception $e)
        {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}