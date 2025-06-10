<?php

namespace Enrisezwolle\FilamentCms\Filament\Actions;

use Closure;
use Enrisezwolle\FilamentCms\Contracts\IsTranslatable;
use Enrisezwolle\FilamentCms\Traits\HasVisibility;
use Enrisezwolle\FilamentCms\Traits\Publishable;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Model;

class FillLanguageAction extends ActionGroup
{
    protected string $resourceClass;

    protected Closure $conceptClosure;

    public static function make($actions = []): static
    {
        $instance = parent::make([])
            ->button()
            ->label(__('filament-cms::actions.fill-language.label'))
            ->icon('heroicon-s-language');

        if (empty($actions)) {
            foreach (config('filament-cms.languages', []) as $language) {
                $actions[] = Action::make($language)
                    ->label(strtoupper($language))
                    ->icon(fn(Model|IsTranslatable $record) => static::getSubActionIcon($instance, $record, $language))
                    ->url(fn (Model|IsTranslatable $record) => static::getSubActionUrl($instance, $record, $language));
            }
        }

        $instance->actions($actions);

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

    protected static function getSubActionIcon(self $actionGroup, Model|IsTranslatable $model, string $locale): string
    {
        // TODO: Refactor to be dynamic (both blog relation and locale name)
        $instance = $model->blogs()->firstWhere('locale', $locale);

        if ($instance) {
            if ($actionGroup->evaluate($actionGroup->conceptClosure, ['instance' => $instance])) {
                return 'heroicon-o-eye-slash';
            }

            return 'heroicon-s-check';
        }

        return 'heroicon-s-x-mark';
    }

    protected static function getSubActionUrl(self $actionGroup, Model|IsTranslatable $model, string $locale): string
    {
        // TODO: Refactor to be dynamic (both blog relation and locale name)
        $instance = $model->blogs()->firstWhere('locale', $locale);
        $resource = $actionGroup->getResourceClass();

        if ($instance) {
            if ($resource::hasPage('view-language')) {
                return $resource::getUrl('view-language', [
                    'record' => $model->getKey(),
                    'locale' => $locale
                ]);
            }

            if ($resource::hasPage('edit-language')) {
                return $resource::getUrl('edit-language', [
                    'record' => $model->getKey(),
                    'locale' => $locale
                ]);
            }
        }

        return $resource::getUrl('create-language', [
            'commonId' => $model->getKey(),
            'locale' => $locale
        ]);
    }
}
