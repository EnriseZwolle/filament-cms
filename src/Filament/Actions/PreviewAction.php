<?php

namespace Enrisezwolle\FilamentCms\Filament\Actions;

use Enrisezwolle\FilamentCms\Facades\FilamentCms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Model;

class PreviewAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'open';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-cms::actions.preview.label'));
        $this->tooltip(__('filament-cms::actions.preview.tooltip'));

        $this->icon('heroicon-m-arrow-right-start-on-rectangle');
        $this->groupedIcon('heroicon-m-arrow-right-start-on-rectangle');

        $this->url(function (Model $record) {
            $hash = FilamentCms::hashModel($record::class, $record->getKey());

            return url($record->getFullPath()."?preview=$hash");
        }, true);
    }
}
