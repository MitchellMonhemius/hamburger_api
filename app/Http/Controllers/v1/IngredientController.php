<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\v1\IngredientService;

class IngredientController extends Controller
{
    protected $ingredients;
    public function __construct(IngredientService $service)
    {
        $this->ingredients = $service;

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
        $data = $this->ingredients->getIngredients($parameters);

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
        $this->ingredients->validate($request->all());

        try
        {
            $ingredient = $this->ingredients->createIngredient($request);
            return response()->json($ingredient, 201);
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
        $parameters['id'] = $id;

        //call service
        $data = $this->ingredients->getIngredients($parameters);

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
        $this->ingredients->validate($request->all());

        try
        {
            $ingredient = $this->ingredients->updateIngredient($request, $id);
            return response()->json($ingredient, 200);
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
            $ingredient = $this->ingredients->deleteIngredient($id);
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