# Categories

This package provides a `Categories` model and trait that can be used to attach categories to other Eloquent models through a polymorphic many-to-many relationship.

The category model supports nested set structure, translations, slugs, and tags through the traits and packages already used by the project.

## Installation

```bash
composer require datasdk/categories
```

## Using The Category Model

Import the model like this:

```php
use MyProject\Categories\Models\Categories;
```

Example:

```php
$category = Categories::create([
    'name' => ['da' => 'Nyheder', 'en' => 'News'],
    'description' => ['da' => 'Alle nyheder', 'en' => 'All news'],
    'type' => 'posts',
    'active' => true,
]);
```

## Usage On A Model

Add the trait to a model that should support categories:

```php
use MyProject\Categories\Traits\Categories;

class Post extends Model
{
    use Categories;
}
```

## Relationships

`categories()`

Returns the model's categories through the `categories_models` table.

```php
$post->categories;
```

`entries($class)`

Used from the category model to retrieve models of a specific class that are attached to the category.

```php
$category->entries(Post::class)->get();
```

`model()`

Returns the polymorphic model relationship from the category model.

## Trait Methods

`setCategories($categoryIds)`

Syncs a model with a list of category IDs.

```php
$post->setCategories([1, 2, 3]);
```

`setCategory($categoryIds)`

Alias for `setCategories()`.

```php
$post->setCategory([1]);
```

`attachCategory(...$categories)`

Adds one or more categories without removing existing categories.

```php
$post->attachCategory($category);
$post->attachCategory(1, 2, 3);
```

## Query Scopes

`withCategories(array $ids)`

Returns models attached to one or more categories.

```php
Post::withCategories([1, 2])->get();
```

`withCategory($id)`

Returns models attached to a single category.

```php
Post::withCategory(1)->get();
```

## Category Model Methods

`getAllChildren($ids = null)`

Returns all child category IDs for one or more categories. The method accepts both IDs and slugs.

```php
$ids = Categories::getAllChildren([1, 5]);
```

`addInclude(string $type, string $class)`

Registers a model class as a category include type in the Laravel container.

```php
Categories::addInclude('posts', Post::class);
```

When the request contains `children=posts`, the category can then retrieve related entries for that type.

## Tags

The category model uses Spatie's `HasTags` trait. Use Spatie's tag methods, for example:

```php
$category->syncTags(['featured', 'frontpage']);
$category->attachTag('important');
$category->tags;
```
