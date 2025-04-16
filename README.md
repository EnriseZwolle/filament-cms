# TODO's

* ~~Merge visibility and publish plugins with each enabled conditionally~~
* ~~Make publish from/until optionally required~~
* ~~Preview~~
* ~~Frontend scopes~~
* ~~Labels~~
* ~~Frontend handling blocks~~
* ~~Translations~~
* ~~Update Readme~~
* ~~SEO Frontend~~
* Block seeder
* Menu
* Title + slug plugin
* Search
* Testen schrijven
* Move resource name from controller to trait
  * Update views section of readme
* Move registering observer to trait instead of config/provider
  * Update docs

# CMS features for filament

This package is a simple way to turn your filament installation into a fully featured cms with build-in routing, seo,
block module, preview functionality, visibility and publish helpers and many more features.

It gives the developers full control of which features are used and when and is easily modifiable. Use our wholesale 
features or write your own implementation, everything is possible!

## Installation

You can install the package via composer:

```bash
composer require enrisezwolle/filament-cms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-cms-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-cms-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-cms-views"
```

## Creating a frontend-viewable resource

Any model can become viewable in the frontend, the only requirement is that the model has a slug field. This field 
needs to exist, but can have any name. The default column name is `slug`, but can easily be overwritten on a model 
basis.

### Setting up the model

First we need to make the model accessible by implementing the `IsSluggable` interface and adding the `Sluggable` trait.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Enrisezwolle\FilamentCms\Traits\Sluggable;
use Enrisezwolle\FilamentCms\Contracts\IsSluggable;

class Page extends Model implements IsSluggable
{
    use Sluggable;

    protected $guarded = [];
}
```

As mentioned previously, by default it uses a database column called `slug` by default to build the slugs. This can 
be changed by overriding the `sluggableAttribute()` method on the model.

Additionally this model needs to be registered in the `filament-cms.models` config.

```php
'models' => [
    \App\Models\Page::class,
],
```

When both are configured correctly it will configure observers automatically and write the slugs to the lookup table.

### Routing

For routing, simply add `Route::filamentCms();` as the last line in your routing files. This implements a 
[fallback](https://laravel.com/docs/12.x/routing#fallback-routes) route and as such is not compatible with other 
fallback routes.

With both the model and the routing configured the resources can be viewed in the frontend.

### Views

To be able to view pages in the frontend you obviously also need views. These are specified in the `filament-cms.views`.

By default the following structure is provided, but can easily be modified to your liking:

```php
'views' => [
    'pages.{resource}.{label}',
    'pages.{resource}.show',
    'pages.{resource}',
    '{resource}.{label}',
    '{resource}.show',
    '{label}',
    '{resource}',
    'index',
],
```

It grabs the first existing view specified in the array. It can contain two wildcards, `{resource}` and `{label}`. 
The `resource` wildcard will be the sluggified name of the model, so for the `News` model `'pages.{resource}.show'` 
will become `'pages.news.show'`.

The same applies to label. Models can receive a system label (more on this later), and the `{label}` will be 
replaced by this label if it exists. If a model has no system label these lines will be filtered. 

These views have access to the `$model` variable. This variable contains the sluggable model that is currently being 
viewed.

### System Labels

In the previous section we briefly mentioned system labels. Any model can receive system labels by simply adding 
`SystemLabelable` trait it.

These labels are intended to be used for specific records that are essential the system that are both required or 
have custom implementations. Think for example about a news index page which is managed through a `Page` resource 
but has news overview which deviates from the display of regular pages.

In the previous section we discussed how you can create a specific view for a labelled 
page.

As such, resources with a system label cannot be deleted. You need to specify this on the filament resource as well, 
for example:

```php
public static function canDelete(Model $record): bool
{
    return !(method_exists($record, 'hasSystemLabel') && $record->hasSystemLabel());
}
```

Sometimes you want to create links to a system page or want to quickly fetch a model through a label. This can be 
done using the `get_model_for_label` helper that is globally accessable.

## Nested resources

The resource paths can be build recursively. For example, you have a `Page` with the slug `/team` nested under 
another `Page` with the slug `/about` the desired full path would be `/about/team`, which is entirely possible.

This is build-in in the `Sluggable` trait and just requires you to overwrite a few methods to define the parent and 
children of a resource. In the example below we have a `Page` model which can be nested under other pages. This 
could also be any other resource, the sky is the limit!

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Enrisezwolle\FilamentCms\Traits\Sluggable;
use Enrisezwolle\FilamentCms\Contracts\IsSluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model implements IsSluggable
{
    use Sluggable;
    
    protected $guarded = [];

    public function page(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class);
    }

    public function sluggableParent(): ?string
    {
        return 'page'; // Name of the relation that determines the parent
    }

    public function sluggableChildren(): ?string
    {
        return 'children'; // Name of the relation that determines the children
    }
}
```

In the example above we create a model with a `parent` and `children` relation. By overwriting the `sluggableParent()
` and `sluggableChildren()` methods we can configure the relations used to build up the paths. In the xample we use 
the names `parent` and `children`, but these names are entirely up to the developer.

When updating a slug it will automatically update any children as well in the lookup table.

## Traits

Filament CMS comes with a few handy-to-use traits to help quickly set-up features such as visibility and seo. Other 
traits such as `Sluggable` and `SystemLabelable` have been discussed earlier and wont be handled in-depths here.

### Visible & Publishable

Visibility and publishable go hand in hand and as such it is set up in a way it does.

#### Visibility

Visibility can be added to any model by adding the `HasVisibility` trait to it.

By default it uses the `visible` column on the model, but this can easily be changed by overriding the 
`getVisibleKey()` method on the model. This method should return the boolean column name.

This trait also adds the `visible()` and `invisible()` scopes, as well as a `isVisible()` helper method.

#### Publishable

To add publishability to a model simply add the `Publishable` trait to it. 

The default columns used to determine the start and end dates for publishing are `publish_from` and `publish_until`, 
and just like the visibility trait can these easily be overwritten. The associated methods are `getPublishFromKey()` 
and `getPublishUntilKey`.

The trait also comes with three scopes; `published()`, `unpublished()` and `orderByPublishedAt()`.

The `orderByPublishedAt` scope orders resources by, you guessed it, the published at column. However, this can be 
nullable and therefore it is not always desirable to sort by just this column, for these cases you want a fallback. 
The default column for this is `created_at`, but like everything else, this can easily be changed by overriding the 
`getPublishFromFallback()` method.

Finally it adds the `isPublished` helper method to check if a specific model is actually published a that moment.

#### Fields

We also provide a default implementation to add fields for both visibility and published quickly to your filament 
resources. These are merged into a singular fieldset as these are often paired and can be turned on or off separately. 
Simply use the FilamentCms facade to add these fields to your filament resource tables. Ofcourse you are also free 
to write your own implementation.

```php
FilamentCms::visibilityFields(
    model: self::$model,
    visibility: true,
    publishable: true,
    publishFromRequired: true,
    publishUntilRequired: false,
),
```

### SEO & Open Graph

The seo plugin is common in any front-end facing website can easily be added to any model by adding the `Seoable` 
trait to it.

Next you need to add the fields to your filament resource tables. Using the `FilamentCms` facade you can add it with 
`FilamentCms::seoFields()`. This adds a fieldset with all SEO and open graph related fields.

To load the SEO data in the front-end start by adding a seo stack to your head in your template `@stack('seo')`. 
Next add the following to your blade file:

```bladehtml
<x-filament-cms::seo 
    :model="$model" 
    :suffix="true"
    :robots="[]"
    :url="url()->current()"
    :image="$model->image"
/>
```

The model with the seo trait is a required variable. Additionally it accepts several other properties. The suffix 
property can be either a boolean or a string. When set to true it simply adds the app name to the title, for example 
`Contact | Enrise`. Robots are populated automatically based on if `no follow` or `no index` are enabled. Additional 
robots can be added as well and will be merged with the previous mentioned ones. Url will default to `url()->current
()`, but if the open-graph url needs to deviate it can be specified by overriding the url property. Finally, when no 
open-graph image is set in the CMS you often want to default back to the resources image. That can be done by 
supplying an image property. It will prioritize the SEO image.

## Scopes

With the model configured and the traits set-up we want the prevent access to viewing pages when certain conditions 
are not met, for example when a page is not supposed to be visible or not yet published.

This is very simple to add. To any model implementing `Sluggalbe` add a static `$frontendScopes` array containing 
the names of all the scopes that specifically need to be added when viewing the page in the front-end. When using 
the visibility and published traits it would look a little something like this:

```php
 public static array $frontendScopes = [
    'visible',
    'published',
];
```

## Preview

With the front-end scopes applied you might wish to preview a page. This is possible by adding the correct actions 
to your filament resources.

On the index page you need to add the action `Enrisezwolle\FilamentCms\Filament\Actions\Tables\PreviewAction`. For 
a view or edit page this should be `Enrisezwolle\FilamentCms\Filament\Actions\PreviewAction`.

Both actions add a button to either the table or the top menu and allows you to preview the page without making it 
publicly available.

## Blocks

In current year you cannot have a fully featured CMS without having a block module to add more complex page building 
options. Filament CMS is no exception!

### Setting up the column

The data for blocks is typically stored into a json field on the model, please pay special attention to casting it 
correctly.

### Adding it to the resource

The block module can easily be added to any filament resource table, and can even support being loaded multiple 
times on any given resource for multiple fields. To do this add `BlockModule::make('column')` where the variable 
passed is the name of the column it needs to be stored in.

### Block types

The package comes with a few default blocks. These can easily be disabled and new blocks can just as easily be 
created. These can be managed in the `filament-cms.blocks` config.

By default this config has two groups, `active` and `toggle_content`. The `active` group contains all available 
blocks and `toggle_content` contains blocks only available in the `ToggleContent` block.

Blocks can be added to removed to these groups and thus can be configured exactly how you want it.

### Block groups

In addition to the default groups you can also create custom groups. This can be desirable when you want to have 
different implementations of the block module based on the resource. To do this add a new array section to `blocks` in 
the config and add all the blocks that should be available. Blocks can be registered to multiple sections.

To specify which block group needs to be opened you can add the name as a second parameter to the make

```php
BlockModule::make('column', 'group')
```

### Creating your own block types

You can also create your own blocks. These need to extend the `BaseBlock`, which is an abstract class which requires
you to manually implement certain functions. Your IDE can assist with this.

Please be aware that `getType()` should return a value unique from other registered blocks. This means it is 
perfectly possible to override default blocks and use the same type name, as long as the default block is no longer 
registered in the config.
