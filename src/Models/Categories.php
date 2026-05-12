<?php 

namespace MyProject\Categories\Models;

use App\Contracts\Abstracts\ActionModel;
Use App\Traits\Nestable\Nestable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Language\Language;
use App\Contracts\Interfaces\CategoryInterface;
use Spatie\Tags\HasTags;


class Categories extends ActionModel implements CategoryInterface {
    

    use HasTags;
    use Language;
    use Nestable;

    // Parent column reference
    protected $parent = 'parent_id';
    
    // Field used for generating slugs
    public $sluggable = 'name';
    
    // Fields that support translation
    protected $translatable = ['name', 'slug', 'description'];   
    
    // Mass assignable attributes
    protected $fillable = [
        "parent_id",
        "type",
        "name",
        "description",
        "sorting",
        "active",
    ];

    // Attributes that should be hidden from JSON serialization
    protected $hidden = ["_lft", "_rgt", "pivot"];
    
    // Columns that can be searched
    protected $searchableColumns = ['name', 'slug', 'description', 'type']; 
    
    // Static property to store included categories
    protected static $category_includes = [];


    // Get the morph class for polymorphic relations
    public function getMorphClass()
    {
        return self::class;
    }

     public function model()
    {
        return $this->morphTo();
    }

    // Define a many-to-many polymorphic relationship
    public function entries($class){

        return $this->morphedByMany($class, 'model','categories_models','category_id');

    }
   
    // Get all children categories, including descendants, based on IDs or slugs
    public static function getAllChildren($ids = null){


        if(!$ids){ return []; }

        if(!is_array($ids)){ $ids = [$ids]; }

        $category = collect($ids)->map(function($idOrSlug){

            if(!$category = Categories::findBySlugOrId($idOrSlug)){ return []; }

            return $category->descendantsAndSelf($category->id)->toFlatTree()->pluck('id')->toArray();

        })
        ->flatten()
        ->toArray();   
        

        return $category;

    }


    // Get children categories dynamically based on request parameters
    public function getChildrenAttribute(){


        $req = request();

        if ($req->has("children")) {
            
            $type = $req->children;
            
            $limit = $req->limit ?? null;
            
            $key = 'categories.' . $type;
        
            // Check if the binding exists in the application container
            if (app()->bound($key)) {
                
                $class = app()->make($key);
                
                $res[$type] = $this->entries($class)->take($limit)->get()->translate();
                
            } else {
                // Handle the error by returning an empty array
                $res[$type] = [];
            }
        }

        return $res ?? null;

    }


    // Add a category type to the application's bindings
    public static function addInclude(string $type, string $class)
    {
        app()->singleton('categories.'.$type, function () use($class) {
            return $class;
        });

    }

}



// Extend Categories to create a Category class
class Category extends Categories {}


// Define the pivot model for categories and related models
class CategoriesModels extends Model {


    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        "category_id",
        "model_type",
        "model_id"
    ];

    // Define polymorphic relationship to category
    public function category(){
        return $this->morphTo("model_type","model_id");
    }

}
