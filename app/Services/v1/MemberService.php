<?php

namespace App\Services\v1;

use Validator;

use App\Members;


class MemberService
{
	protected $supportedIncludes = [];

	protected $clauseProperties = [
		'id',
		'group_id',
		'chef_id'
	];

    protected $rules = [
        'name' => 'required'
    ];


	public function validate($members)
	{
		$validator = Validator::make($member, $this->rules);
		$validator->validate();
	}	




	public function getMembers($parameters)
	{
		if (empty($parameters))
		{
			return $this->filterMembers(Members::all());
		}

		$withKeys 		= $this->getWithKeys($parameters);
		$whereClauses 	= $this->getWhereClauses($parameters);

		$query = Members::with($withKeys)->where($whereClauses)->get();

		return $this->filterMembers($query, $withKeys);
	}


    public function createMember($req)
    {

    }

    public function updateMember($req, $id)
    {

    }

    public function deleteMember($id)
    {
    	$member = Members::where('id', $id)->firstOrFail();
    	
        $member->delete();
    }





	protected function filterMembers($members, $keys = [])
	{
		$data = [];

		foreach ($members as $member)
		{

			$entry = [
				'id'  		=> $member->id,
				'group_id'	=> $member->group_id,
				'chef_id'	=> $member->chef_id
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
















