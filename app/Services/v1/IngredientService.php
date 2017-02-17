<?php

namespace App\Services\v1;

use Validator;

use App\Ingredients;



class IngredientService
{
	protected $supportedIncludes = [];

	protected $clauseProperties = [
		'id',
		'burger_id'
	];

    protected $rules = [
        'name' => 'required'
    ];



	public function validate($ingredients)
	{
		$validator = Validator::make($ingredient, $this->rules);
		$validator->validate();
	}	




	public function getIngredients($parameters)
	{
		if (empty($parameters))
		{
			return $this->filterIngredients(Ingredients::all());
		}

		$withKeys 		= $this->getWithKeys($parameters);
		$whereClauses 	= $this->getWhereClauses($parameters);

		$query = Ingredients::with($withKeys)->where($whereClauses)->get();

		return $this->filterIngredients($query, $withKeys);
	}


    public function createIngredient($req)
    {
        $ingredient = new Ingredient();

        $ingredient->name 			= $req->input('name');
        $ingredient->burger_id 		= $req->input('burger_id');

        $ingredient->save();

        return $this->filterIngredients([$ingredient]);
    }

    public function updateIngredient($req, $id)
    {
    	$ingredient = Ingredients::where('id', $id)->firstOrFail();

        $ingredient->name 			= $req->input('name');
        $ingredient->burger_id 		= $req->input('burger_id');

        $ingredient->save();

        return $this->filterIngredients([$ingredient]);
    }

    public function deleteIngredient($id)
    {
    	$ingredient = Ingredients::where('id', $id)->firstOrFail();

        $ingredient->delete();
    }





	protected function filterIngredients($ingredients, $keys = [])
	{
		$data = [];

		foreach ($ingredients as $ingredient)
		{

			$entry = [
				'id'  			=> $ingredient->id,
				'burger_id'  	=> $ingredient->burger_id,
				'name'			=> $ingredient->name
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
















