<?php

namespace Enrisezwolle\FilamentCms\Http\Controllers;

use Enrisezwolle\FilamentCms\Models\ResourceLookup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ViewResourceController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __invoke(string $path)
    {
        // TODO:
        //  Add scopes
        //  Add preview
        //  Add Labels
        //  Add visible trait
        //  Add publishable trait

        $model = ResourceLookup::getModelForPath($path);

        abort_if(is_null($model), 404);

        return View::first($this->getViews($model), ['model' => $model]);
    }

    protected function getViews(Model $model): array
    {
        // TODO: Move resource name to interface/trait
        $resourceName = Str::of(get_class($model))->afterLast('\\')->lower()->slug()->toString();

        $views = config('filament-cms.views');

        return collect($views)
            ->map(fn (string $view) => Str::replace('*', $resourceName, $view))
            ->toArray();
    }
}
