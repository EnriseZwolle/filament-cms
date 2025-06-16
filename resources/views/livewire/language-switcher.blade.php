@if(count($this->languages()))
    <div>
        <select name="" id="" wire:model.live="activeLocale">
            @foreach($this->languages() as $language)
                <option value="{{ $language }}">{{ strtoupper($language) }}</option>
            @endforeach
        </select>
    </div>
@endif
