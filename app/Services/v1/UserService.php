<?php

namespace App\Services\v1;

use Validator;

use App\User;


class UserService
{
	protected $supportedIncludes = [];

	protected $clauseProperties = [
		'id',
		'name'
	];

    protected $rules = [
        'name' => 'required'
    ];


	public function validate($users)
	{
		$validator = Validator::make($user, $this->rules);
		$validator->validate();
	}	




	public function getUsers($parameters)
	{
		if (empty($parameters))
		{
			return $this->filterUsers(User::all());
		}

		$withKeys 		= $this->getWithKeys($parameters);
		$whereClauses 	= $this->getWhereClauses($parameters);

		$query = User::with($withKeys)->where($whereClauses)->get();

		return $this->filterUsers($query, $withKeys);
	}


    public function createUser($req)
    {
        $user = new User();

        $user->name 			= $req->input('name');
        $user->image_url 		= $req->input('image_url');

        $user->save();

        return $this->filterUsers([$user]);
    }

    public function updateUser($req, $id)
    {
    	$user = User::where('id', $id)->firstOrFail();

        $user->name 			= $req->input('name');
        $user->image_url 		= $req->input('image_url');

        $user->save();

        return $this->filterUsers([$user]);
    }

    public function deleteUser($id)
    {
    	$user = User::where('id', $id)->firstOrFail();
    	
        $user->delete();
    }





	protected function filterUsers($users, $keys = [])
	{
		$data = [];

		foreach ($users as $user)
		{

			$entry = [
				'id'  		=> $user->id,
				'name'		=> $user->name,
				'rank'		=> $user->rank,
				'score'		=> $user->score,
				'image'		=> $user->image_url
			];

			$data[] = $entry;
		}

		return $data;
	}

	protected function getWithKeys($parameters)
	{
		$withKeys = [];

		if (isset($parameters['include']))
		{
			$includeParms = explode(',', $parameters['include']);
			$includes = array_intersect($this->supportedIncludes, $includeParms);
			$withKeys = array_keys($includes);
		}

		return $withKeys;
	}

	protected function getWhereClauses($parameters)
	{
		$clause = [];

		foreach ($this->clauseProperties as $prop)
		{
			if (in_array($prop, array_keys($parameters)))
			{
				$clause[$prop] = $parameters[$prop];
			}
		}

		return $clause;
	}
}
















