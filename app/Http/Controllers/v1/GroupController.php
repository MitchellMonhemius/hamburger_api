<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\v1\GroupService;

class GroupController extends Controller
{
    protected $groups;
    public function __construct(GroupService $service)
    {
        $this->groups = $service;

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
        $data = $this->groups->getGroups($parameters);

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
        $this->groups->validate($request->all());

        try
        {
            $group = $this->groups->createGroup($request);
            return response()->json($group, 201);
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
        $data = $this->groups->getgroups($parameters);

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
        $this->groups->validate($request->all());

        try
        {
            $group = $this->groups->updateGroup($request, $id);
            return response()->json($group, 200);
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
            $group = $this->groups->deleteGroup($id);
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