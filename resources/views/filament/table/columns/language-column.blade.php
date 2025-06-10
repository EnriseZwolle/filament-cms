<div
    {{
        $attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-ta-text flex w-full gap-x-2 gap-y-1',
            ])
    }}
>
    @foreach($getLanguages() as $language)
        <a
            href="{{ $getLanguageRoute($language) }}"
            style="display: flex; background-color: {{ $getLanguageColor($language) }}; padding: 0 12px; align-items: center; justify-content: center; border-radius: 14px; height: 28px; font-size: 12px; color: white;"
        >
            {{ $language }}
        </a>
    @endforeach
</div>
