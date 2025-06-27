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

<!-- Page Builder Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="{{ asset('assets/js/page-builder.js') }}"></script>

@switch($template->name)
    @case('Default Template')
    @break

    @case('Wicked Blocks')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.3.0/alpine-ie11.min.js"></script>
    @break

    @case('Barista Cafe')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
        {{-- <script src="templates/barista-cafe/js/jquery.min.js"></script> --}}
        <script src="templates/barista-cafe/js/bootstrap.min.js"></script>
        <script src="templates/barista-cafe/js/jquery.sticky.js"></script>
        <script src="templates/barista-cafe/js/click-scroll.js"></script>
        <script src="templates/barista-cafe/js/vegas.min.js"></script>
        <script src="templates/barista-cafe/js/custom.js"></script>
    @break

    @default
@endswitch
