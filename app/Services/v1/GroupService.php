<?php

namespace App\Services\v1;

use Validator;

use App\Groups;
use App\Burgers;


class GroupService
{
	protected $supportedIncludes = [
		'groupBurgers' => 'burgers'
	];

	protected $clauseProperties = [];

    protected $rules = [
        'name' => 'required'
    ];



	public function validate($groups)
	{
		$validator = Validator::make($group, $this->rules);
		$validator->validate();
	}	




	public function getGroups($parameters)
	{
		if (empty($parameters))
		{
			return $this->filterGroups(Groups::all());
		}

		$withKeys 		= $this->getWithKeys($parameters);
		$whereClauses 	= $this->getWhereClauses($parameters);

		$query = Groups::with($withKeys)->where($whereClauses)->get();

		return $this->filterGroups($query, $withKeys);
	}


    public function createGroup($req)
    {
        $group = new Group();

        $group->name = $req->input('name');

        $group->save();

        return $this->filterGroups([$group]);
    }

    public function updateGroup($req, $id)
    {
    	$group = Groups::where('id', $id)->firstOrFail();

        $group->name = $req->input('name');

        $group->save();

        return $this->filterGroups([$group]);
    }

    public function deleteGroup($id)
    {
    	$group = Groups::where('id', $id)->firstOrFail();

        $group->delete();
    }





	protected function filterGroups($groups, $keys = [])
	{
		$data = [];

		foreach ($groups as $group)
		{
			$entry = [
				'id'  			=> $group->id,
				'name'			=> $group->name
			];

			if (in_array('groupBurgers', $keys))
			{
				$entry['burgers'] = [];

				//get the burgers
				$burgers = Burgers::where('group_id', $group->id)->get();
				foreach ($burgers as $burger)
				{
					array_push($entry['burgers'],$burger->name);
				}
			}

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
















