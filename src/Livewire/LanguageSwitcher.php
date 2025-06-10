<?php

namespace Enrisezwolle\FilamentCms\Livewire;

use Enrisezwolle\FilamentCms\Contracts\IsTranslatable;
use Enrisezwolle\FilamentCms\Models\ResourceLookup;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public ?string $initialLocale = null;

    public ?string $path = null;

    public ?string $model = null;

    public string $activeLocale;

    public function mount(): void
    {
        if (request()->lookupLocale) {
            $this->initialLocale = request()->lookupLocale;
        }

        if (request()->lookupPath) {
            $this->path = request()->lookupPath;
        }

        $this->activeLocale = $this->initialLocale ?? app()->getLocale();
    }

    public function languages(): array
    {
        return config('filament-cms.languages', []);
    }

    public function updatedActiveLocale(string $newLocale): void
    {
        // If current route does not contain locale simply reload page
        if (! $this->initialLocale) {
            $this->redirect($this->path);
        }

        // Attempt to find the lookup the initial page in the lookup table
        $lookup = ResourceLookup::query()
            ->where('locale', $this->initialLocale)
            ->where('path', $this->path)
            ->first();

        // If no page cannot be found in lookup redirect back to home
        if (is_null($lookup)) {
            $this->redirect('/');
            return;
        }

        // Attempt to find the common model through the lookup
        $common = $lookup
            ?->model
            ?->common;

        // If no common model can be found for current page redirect to home
        if (is_null($common)) {
            $this->redirect('/');
            return;
        }

        // Attempt to find a translated page for the new
        $translated = $common
            ?->details()
            ?->where($common->details()->getModel()->translatableColumn(), $this->activeLocale)
            ?->scopes($common->details()->getModel()::$frontendScopes ?? [])
            ?->first();

        // If translated instance can be found redirect to its url
        if ($translated && $translated instanceof IsTranslatable) {
            $this->redirect($translated->getTranslatedUrl());
            return;
        }

        // If everything else fails, redirect back to home
        $this->redirect('/');
    }

    public function render(): View
    {
        return view('filament-cms::livewire.language-switcher');
    }
}
