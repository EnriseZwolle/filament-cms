<?php

namespace Enrisezwolle\FilamentCms\Filament\Resources\Pages;

use Enrisezwolle\FilamentCms\Contracts\IsTranslatable;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreateTranslatableRecord extends CreateRecord
{
    protected static string $detailModel;

    public Model|IsTranslatable $common;
    public mixed $commonId;
    public string $locale;

    public function getModel(): string
    {
        return static::$detailModel;
    }

    public function getTitle(): string|Htmlable
    {
        return parent::getTitle() . ' (' . strtoupper($this->locale) . ')';
    }

    public function mount(): void
    {
        $this->commonId = request()->route('commonId');
        $this->locale = request()->route('locale');

        throw_if(! (new static::$detailModel) instanceof IsTranslatable);

        // Make sure model actually exists
        $this->common = static::$resource::getModel()::findOrFail($this->commonId);

        parent::mount();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var IsTranslatable $instance */
        $instance = new static::$detailModel;

        $data[$instance->common()->getForeignKeyName()] = $this->commonId;
        $data[$instance->translatableColumn()] = $this->locale;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        if ($resource::hasPage('view-language')) {
            return $resource::getUrl('view-language', [
                'record' => $this->commonId,
                'locale' => $this->locale,
                ...$this->getRedirectUrlParameters()
            ]);
        }

        if ($resource::hasPage('edit-language')) {
            return $resource::getUrl('edit-language', [
                'record' => $this->commonId,
                'locale' => $this->locale,
                ...$this->getRedirectUrlParameters()
            ]);
        }

        return parent::getRedirectUrl();
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $url = null;
        $recordName = $this->common->getTitle();

        if ($resource::hasPage('view')) {
            $url = $resource::getUrl('view', [
                'record' => $this->commonId,
                ...$this->getRedirectUrlParameters()
            ]);
        } elseif ($resource::hasPage('edit')) {
            $url = $resource::getUrl('edit', [
                'record' => $this->commonId,
                ...$this->getRedirectUrlParameters()
            ]);
        }

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
            ...([$url => $recordName]),
            ...(filled($breadcrumb = $this->getBreadcrumb()) ? [$breadcrumb] : []),
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        return $breadcrumbs;
    }
}
