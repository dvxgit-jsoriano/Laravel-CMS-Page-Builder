{{--
|--------------------------------------------------------------------------
| Template-Specific Styles and Scripts
|--------------------------------------------------------------------------
|
| This Blade file is responsible for injecting customized styles and scripts
| depending on the currently selected template in the Page Builder.
|
| HOW IT WORKS:
| - The Page Builder controller will determine the active template.
| - Based on the template, this Blade file will include the necessary CSS/JS
|   files or inline code required for proper rendering and functionality.
--}}

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

@switch($template->name)
    @case('Default Template')
        <script src="https://cdn.tailwindcss.com"></script>
    @break

    @case('Wicked Blocks')
        <script src="https://cdn.tailwindcss.com"></script>
    @break

    @case('Hotel Diavox')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap"
            rel="stylesheet">
        <link href="{{ asset('templates/hotel-diavox/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('templates/hotel-diavox/css/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('templates/hotel-diavox/css/vegas.min.css') }}" rel="stylesheet">
        <link href="{{ asset('templates/hotel-diavox/css/tooplate-barista.css') }}" rel="stylesheet">
    @break

    @default
@endswitch
