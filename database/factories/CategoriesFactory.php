<?php

namespace DataSDK\Categories\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use DataSDK\Categories\Models\Categories;

class CategoriesFactory extends Factory
{

    protected $model = Categories::class;

    public function definition()
    {

        return [
            'parent_id' => null, // Hvis du vil have underkategorier, kan du generere en parent_id dynamisk
            'type' => $this->faker->randomElement(['blog', 'product', 'news']),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'sorting' => $this->faker->numberBetween(1, 100),
            'active' => $this->faker->boolean,
        ];

    }
    

    public function withParent($parentId)
    {

        return $this->state([
            'parent_id' => $parentId,
        ]);

    }
    
}
