<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\v1\UserService;

class UserController extends Controller
{
    protected $users;
    public function __construct(UserService $service)
    {
        $this->users = $service;

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
        $data = $this->users->getUsers($parameters);

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
        $this->users->validate($request->all());

        try
        {
            $user = $this->users->createUser($request);
            return response()->json($user, 201);
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
        $data = $this->users->getUsers($parameters);

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
        $this->users->validate($request->all());

        try
        {
            $user = $this->users->updateUser($request, $id);
            return response()->json($user, 200);
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
            $user = $this->users->deleteUser($id);
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