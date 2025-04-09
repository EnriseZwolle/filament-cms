<?php

namespace Enrisezwolle\FilamentCms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

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

        $this->url(fn ($record) => url($record->getFullPath()), true);
    }
}
