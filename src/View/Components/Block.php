<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\View\Components;

use Closure;
use Enrisezwolle\FilamentCms\Filament\Plugins\BlockModule;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Block extends Component
{
    /**
     * The block to render.
     *
     * @var object
     */
    public mixed $block;

    /**
     * The block type.
     */
    public string $type;

    /**
     * Create the component instance.
     *
     * @param  object  $block
     * @return void
     */
    public function __construct(mixed $block)
    {
        $this->type = str_replace('_', '-', $block['type']);

        $this->block = BlockModule::reconstructBlock($this->type, $block['data']);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (view()->exists("components.blocks.{$this->type}")) {
            return view("components.blocks.{$this->type}");
        }

        if (view()->exists("filament-cms::components.blocks.{$this->type}")) {
            return view("filament-cms::components.blocks.{$this->type}");
        }

        return view('filament-cms::components.blocks.default');
    }
}
