<?php

namespace Enrisezwolle\FilamentCms\Http\Controllers;

use Enrisezwolle\FilamentCms\Facades\FilamentCms;
use Enrisezwolle\FilamentCms\Models\ResourceLookup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ViewResourceController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __invoke(Request $request, string $path)
    {
        $lookup = ResourceLookup::query()
            ->where('path', $path)
            ->firstOrFail();

        // Do not apply frontend scopes when previewing
        $scopes = $this->isPreview($request, $lookup) ? [] : ($lookup->model()->getModel()::$frontendScopes ?? []);

        $model = $lookup->model()
            ->scopes($scopes)
            ->firstOrFail();

        return View::first($this->getViews($model), ['model' => $model]);
    }

    protected function isPreview(Request $request, ResourceLookup $lookup): bool
    {
        $preview = $request->get('preview');

        if (! filled($preview)) {
            return false;
        }

        return $preview === FilamentCms::hashModel($lookup->model()->getModel()::class, $lookup->model_id);
    }

    protected function getViews(Model $model): array
    {
        $resourceName = Str::of(get_class($model))->afterLast('\\')->lower()->slug()->toString();
        $label = $model->systemLabel?->label;

        $views = config('filament-cms.views');

        if (! filled($label)) {
            $views = array_filter($views, fn (string $view) => ! Str::of($view)->contains('{label}'));
        }

        return collect($views)
            ->map(function (string $view) use ($resourceName, $label) {
                return Str::of($view)
                    ->replace('{resource}', $resourceName)
                    ->replace('{label}', $label)
                    ->toString();
            })
            ->toArray();
    }
}
