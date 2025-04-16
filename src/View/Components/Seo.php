<?php

declare(strict_types=1);

namespace Enrisezwolle\FilamentCms\View\Components;

use Closure;
use Enrisezwolle\FilamentCms\Contracts\IsSluggable;
use Enrisezwolle\FilamentCms\Traits\Seoable;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class Seo extends Component
{
    public function __construct(
        public Model|Seoable|null $model = null,
        public bool|string|null $suffix = null,
        public array $robots = [],
        public string $type = 'website',
        public ?string $url = null,
        public ?string $image = null,
    )
    {

    }

    public function getSeoTitle(): string
    {
        $prefix = $this->model->seo?->seo_title ?? $this->model->getTitle();

        $suffix = null;

        if (is_string($this->suffix)) {
            $suffix = $this->suffix;
        } elseif (is_bool($this->suffix) && $this->suffix === true) {
            $suffix = config('app.name');
        }

        return implode(' | ', array_filter([$prefix, $suffix]));
    }

    public function getOgTitle(): string
    {
        $prefix = $this->model->seo?->og_title ?? $this->model->getTitle();

        $suffix = null;

        if (is_string($this->suffix)) {
            $suffix = $this->suffix;
        } elseif (is_bool($this->suffix) && $this->suffix === true) {
            $suffix = config('app.name');
        }

        return implode(' | ', array_filter([$prefix, $suffix]));
    }

    public function getSeoDescription(): string
    {
        $description = $this->model->seo?->description ?? $this->model->getDescription();

        return Str::limit($description, 170);
    }

    public function getOgDescription(): string
    {
        $description = $this->model->seo?->description ?? $this->model->getDescription();

        return Str::limit($description, 200);
    }

    public function getRobots(): array
    {
        $robots = [];

        if ($this->model->seo?->noindex || ! app()->environment('production')) {
            $robots[] = 'noindex';
        }

        if ($this->model->seo?->nofollow || ! app()->environment('production')) {
            $robots[] = 'nofollow';
        }

        return array_values(array_unique(array_merge($this->robots, $robots)));
    }

    public function getImage(): ?string
    {
        if ($this->model->seo?->image) {
            return url($this->model->seo?->image);
        }

        if ($this->image) {
            return url($this->image);
        }

        return null;
    }

    public function render(): View|Closure|string
    {
        return view('filament-cms::components.seo');
    }
}
