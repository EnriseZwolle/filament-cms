<?php

namespace Enrisezwolle\FilamentCms\Filament\Resources\Pages;

use Enrisezwolle\FilamentCms\Contracts\IsTranslatableCommon;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class EditTranslatableRecord extends EditRecord
{
    public string $locale;

    protected static string $detailModel;

    public function getTitle(): string|Htmlable
    {
        return parent::getTitle() . ' (' . strtoupper($this->locale) . ')';
    }

    public function getModel(): string
    {
        return static::$detailModel;
    }

    protected function resolveRecord(int|string $key): Model
    {
        $this->locale = request()->route('locale');

        /** @var IsTranslatableCommon $record */
        $record = parent::resolveRecord($key);

        $localeColumn = $record->details()->getModel()->translatableColumn();

        return $record->details()->where($localeColumn, $this->locale)->firstOrFail();
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
        ];

        $common = $this->getRecord()->common;

        if ($common) {
            if ($resource::hasPage('view') && $resource::canView($common)) {
                $breadcrumbs[
                $resource::getUrl('view', ['record' => $common])
                ] = $common->getTitle();
            } elseif ($resource::hasPage('edit') && $resource::canEdit($common)) {
                $breadcrumbs[
                $resource::getUrl('edit', ['record' => $common])
                ] = $common->getTitle();
            } else {
                $breadcrumbs[] = $common->getTitle();
            }
        }

        $breadcrumbs[] = $this->getBreadcrumb();

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        return $breadcrumbs;
    }
}
