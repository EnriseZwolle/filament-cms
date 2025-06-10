<?php

namespace Enrisezwolle\FilamentCms\Filament\Tables\Columns;

use Closure;
use Enrisezwolle\FilamentCms\Traits\HasVisibility;
use Enrisezwolle\FilamentCms\Traits\Publishable;
use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Model;

class LanguageColumn extends Column
{
    protected string $view = 'filament-cms::filament.table.columns.language-column';

    protected string $resourceClass;

    protected Closure $conceptClosure;

    public static function make(string $name = 'details'): static
    {
        $instance = parent::make($name);

        $instance->concept(function (Model $instance) {
            $uses = class_uses($instance);

            if (in_array(HasVisibility::class, $uses) && ! $instance->isVisible()) {
                return true;
            }

            if (in_array(Publishable::class, $uses) && ! $instance->isPublished()) {
                return true;
            }

            return false;
        });

        return $instance;
    }

    public function getLanguages(): array
    {
        return config('filament-cms.languages', []);
    }

    public function resource(string $resourceClass): static
    {
        $this->resourceClass = $resourceClass;

        return $this;
    }

    public function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    public function concept(Closure $callable): static
    {
        $this->conceptClosure = $callable;

        return $this;
    }

    public function getLanguageRoute(string $language): string
    {
        $state = $this->getState() ?? collect();

        if ($state->contains(fn (Model $model) => $model->locale === $language)) {
            return $this->getResourceClass()::getUrl('edit-language', [
                'record' => $this->getRecord()->getKey(),
                'locale' => $language
            ]);
        }

        return $this->getResourceClass()::getUrl('create-language', [
            'commonId' => $this->getRecord()->getKey(),
            'locale' => $language
        ]);
    }

    public function getLanguageColor(string $language): string
    {
        $state = $this->getState() ?? collect();

        if ($instance = $state->firstWhere(fn (Model $model) => $model->locale === $language)) {
            // if is concept
            if ($this->evaluate($this->conceptClosure, ['instance' => $instance])) {
                return '#ffc107';
            }

            return '#28a745';
        }

        return '#9ba6b0';
    }
}
