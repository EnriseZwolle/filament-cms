<?php

namespace Enrisezwolle\FilamentCms\Filament\Actions\Tables;

use Enrisezwolle\FilamentCms\Contracts\IsSluggable;
use Enrisezwolle\FilamentCms\Exceptions\SluggableInterfaceNotImplemented;
use Enrisezwolle\FilamentCms\Facades\FilamentCms;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PreviewAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-cms::actions.preview.label'));
        $this->tooltip(__('filament-cms::actions.preview.tooltip'));

        $this->icon('heroicon-m-arrow-right-start-on-rectangle');
        $this->groupedIcon('heroicon-m-arrow-right-start-on-rectangle');

        $this->url(function (Model|IsSluggable $record) {
            throw_if(! is_subclass_of($record, IsSluggable::class), new SluggableInterfaceNotImplemented(get_class($record)));

            $hash = FilamentCms::hashModel($record::class, $record->getKey());

            return url($record->getFullPath()."?preview=$hash");
        }, true);
    }
}
