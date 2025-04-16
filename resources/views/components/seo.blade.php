@push('seo')
    <title>{{ strip_tags($getSeoTitle()) }}</title>
    <meta name="description" content="{{ strip_tags($getSeoDescription()) }}">
    <meta name="robots" content="{{ implode(',', $getRobots()) }}">

    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ strip_tags($getOgTitle()) }}"/>
    <meta property="og:description" content="{{ strip_tags($getOgDescription()) }}"/>
    <meta property="og:url" content="{{ $url ?? url()->current() }}"/>
    @if($getImage())
        <meta property="og:image" content="{{ $getImage() }}"/>
    @endif
@endpush
