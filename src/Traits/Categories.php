<?php

namespace MyProject\Categories\Traits;

use MyProject\Categories\Models\Categories as CategoryModel;
use MyProject\Categories\Models\CategoriesModels;

trait Categories
{
    /**
     * Many categories for this model
     */
    public function categories()
    {
        return $this->morphToMany(
            CategoryModel::class,
            'model',
            'categories_models',
            'model_id',
            'category_id'
        );
    }


    /**
     * Assign multiple categories
     */
    public function setCategories($category_ids = null)
    {
        $ids = CategoryModel::findMany($category_ids)->pluck("id")->toArray();

        $this->categories()->sync($ids);

        return $this;
    }


    /**
     * Assign a single category
     */
    public function setCategory($category_ids = null)
    {
        return $this->setCategories($category_ids);
    }


    /**
     * Attach one or more categories
     */
    public function attachCategory(...$categories)
    {
        collect($categories)
            ->flatten()
            ->map(fn($category) =>
                is_int($category)
                    ? CategoryModel::findOrFail($category)
                    : $category
            )
            ->each(fn($category) =>
                CategoriesModels::firstOrCreate([
                    'category_id' => $category->id,
                    'model_type' => get_class($this),
                    'model_id'   => $this->id,
                ])
            );

        return $this;
    }


    /**
     * Filter by categories
     */
    public function scopeWithCategories($q, array $ids)
    {
        $q->whereHas('categories', function ($q) use ($ids) {
            $q->whereIn("categories.id", $ids);
        });
    }


    public function scopeWithCategory($q, $id)
    {
        $q->whereHas('categories', function ($q) use ($id) {
            $q->where("categories.id", $id);
        });
    }
}
