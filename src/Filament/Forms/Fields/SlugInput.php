<?php

namespace Enrisezwolle\FilamentCms\Filament\Forms\Fields;

use Closure;
use Filament\Forms\Components\TextInput;

class SlugInput extends TextInput
{
    protected string $view = 'filament-cms::components.forms.fields.slug-input';

    protected string|Closure|null $context = null;

    protected bool $cancelled = false;

    protected Closure $recordSlug;

    protected bool|Closure $readOnly = false;

    protected ?Closure $slugInputModelName = null;

    public function slugInputModelName(?Closure $slugInputModelName): static
    {
        $this->slugInputModelName = $slugInputModelName;

        return $this;
    }

    public function getSlugInputModelName(): ?string
    {
        return $this->evaluate($this->slugInputModelName);
    }

    public function slugInputLabelPrefix(?string $labelPrefix): static
    {
        $this->labelPrefix = $labelPrefix ?? 'translation - slug: ';

        return $this;
    }

    public function getLabelPrefix(): string
    {
        return $this->evaluate($this->labelPrefix);
    }

    public function readOnly(Closure|bool $condition = true): static
    {
        $this->readOnly = $condition;

        return $this;
    }

    public function getReadOnly(): bool
    {
        return $this->evaluate($this->readOnly);
    }

    public function slugInputContext(string|Closure|null $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->evaluate($this->context);
    }

    public function slugInputRecordSlug(Closure $recordSlug): static
    {
        $this->recordSlug = $recordSlug;

        return $this;
    }
}
