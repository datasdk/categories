# Categories

Denne pakke indeholder en `Categories` model og et trait, der kan bruges til at koble kategorier på andre Eloquent modeller via en polymorf many-to-many relation.

Kategorimodellen bruger nested set struktur og understøtter oversættelser, slugs og tags via de traits/pakker, som projektet allerede bruger.

## Installation

```bash
composer require datasdk/categories
```

## Brug Kategorimodellen

Importer modellen sådan:

```php
use MyProject\Categories\Models\Categories;
```

Eksempel:

```php
$category = Categories::create([
    'name' => ['da' => 'Nyheder', 'en' => 'News'],
    'description' => ['da' => 'Alle nyheder', 'en' => 'All news'],
    'type' => 'posts',
    'active' => true,
]);
```

## Brug Trait På En Model

Tilføj traitet på en model, der skal kunne have kategorier:

```php
use MyProject\Categories\Traits\Categories;

class Post extends Model
{
    use Categories;
}
```

## Relationer

`categories()`

Returnerer modellens kategorier via tabellen `categories_models`.

```php
$post->categories;
```

`entries($class)`

Bruges fra kategorimodellen til at hente modeller af en bestemt klasse, der er tilknyttet kategorien.

```php
$category->entries(Post::class)->get();
```

`model()`

Returnerer den polymorfe modelrelation fra kategorimodellen.

## Metoder På Trait

`setCategories($categoryIds)`

Synkroniserer en model med en liste af kategori-id'er.

```php
$post->setCategories([1, 2, 3]);
```

`setCategory($categoryIds)`

Alias til `setCategories()`.

```php
$post->setCategory([1]);
```

`attachCategory(...$categories)`

Tilføjer en eller flere kategorier uden at fjerne eksisterende kategorier.

```php
$post->attachCategory($category);
$post->attachCategory(1, 2, 3);
```

## Query Scopes

`withCategories(array $ids)`

Finder modeller, der er knyttet til en eller flere kategorier.

```php
Post::withCategories([1, 2])->get();
```

`withCategory($id)`

Finder modeller, der er knyttet til én kategori.

```php
Post::withCategory(1)->get();
```

## Metoder På Categories Modellen

`getAllChildren($ids = null)`

Returnerer alle underkategorier for en eller flere kategorier. Metoden accepterer både id'er og slugs.

```php
$ids = Categories::getAllChildren([1, 5]);
```

`addInclude(string $type, string $class)`

Registrerer en modelklasse som en kategori-include type i Laravel containeren.

```php
Categories::addInclude('posts', Post::class);
```

Når requesten indeholder `children=posts`, kan kategorien derefter hente relaterede entries for den type.

## Tags

Kategorimodellen bruger Spaties `HasTags` trait. Brug derfor Spaties metoder, for eksempel:

```php
$category->syncTags(['featured', 'frontpage']);
$category->attachTag('important');
$category->tags;
```
