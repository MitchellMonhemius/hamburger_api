<?php

namespace App\Services\v1;

use Validator;

use App\Burgers;
use App\ingredients;



class BurgerService
{
	protected $supportedIncludes = [
		'burgerIngredients' => 'ingredients'
	];

	protected $clauseProperties = ['name'];

    protected $rules = [
        'name' => 'required'
    ];


	public function validate($burgers)
	{
		$validator = Validator::make($burger, $this->rules);
		$validator->validate();
	}	




	public function getBurgers($parameters)
	{
		if (empty($parameters))
		{
			return $this->filterBurgers(Burgers::all());
		}

		$withKeys 		= $this->getWithKeys($parameters);
		$whereClauses 	= $this->getWhereClauses($parameters);

		$query = Burgers::with($withKeys)->where($whereClauses)->get();

		return $this->filterBurgers($query, $withKeys);
	}


    public function createBurger($req)
    {
        $burger = new Burger();

        $burger->name 			= $req->input('name');
        $burger->image_url 		= $req->input('image_url');

        $burger->save();

        return $this->filterBurgers([$burger]);
    }

    public function updateBurger($req, $id)
    {
    	$burger = Burgers::where('id', $id)->firstOrFail();

        $burger->name 			= $req->input('name');
        $burger->image_url 		= $req->input('image_url');

        $burger->save();

        return $this->filterBurgers([$burger]);
    }

    public function deleteBurger($id)
    {
    	$burger = Burgers::where('id', $id)->firstOrFail();
    	
        $burger->delete();
    }





	protected function filterBurgers($burgers, $keys = [])
	{
		$data = [];

		foreach ($burgers as $burger)
		{

			$entry = [
				'id'  		=> $burger->id,
				'name'		=> $burger->name,
				'href'		=> $burger->image_url
			];

			if (in_array('burgerIngredients', $keys))
			{
				$entry['ingredients'] = [];

				//get the ingredients
				$ingredients = Ingredients::where('burger_id', $burger->id)->get();
				foreach ($ingredients as $ingredient)
				{
					array_push($entry['ingredients'],$ingredient->name);
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
















