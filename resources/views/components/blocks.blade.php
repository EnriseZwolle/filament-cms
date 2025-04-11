@props([
    'blocks',
    'group' => 'active',
])

@if(is_countable($blocks) && count($blocks))
    @foreach($blocks as $block)
        <x-filament-cms::block :block="$block" />
    @endforeach
@endif
