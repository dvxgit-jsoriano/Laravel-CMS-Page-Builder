@extends('layouts.builder')

@section('content')
    <header class="navbar">
        <div class="nav-left">
            <img src="https://cdn-icons-png.flaticon.com/128/17357/17357982.png" alt="Logo" class="logo">
            <span class="title">Page Builder</span>
        </div>
        <div class="nav-right">
            <div class="profile-dropdown">
                <img src="https://cdn-icons-png.flaticon.com/128/2202/2202112.png" alt="Profile" class="profile-btn"
                    id="profileBtn">
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="#">Profile</a>
                    <a href="#">Settings</a>
                    <a href="#">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="builder-main">
        <aside class="builder-sidebar">
            <h2 class="section-title">Components [Template: {{ $template->name ?? 'Default Template' }}]</h2>
            <div class="page-dropdown">
                <h3 class="section-title">Page:</h3>
                <select name="select-page" id="select-page" class="select-page">
                    @if (!empty($pages) && count($pages) > 0)
                        @foreach ($pages as $page)
                            <option value="{{ $page->id ?? '' }}">{{ $page->name ?? '' }}</option>
                        @endforeach
                    @else
                        <option disabled>No pages available</option>
                    @endif
                </select>
            </div>

            <ul id="draggable-list" class="draggable-list">
                <li data-block-type="block-navbar">Navigation Bar</li>
                <li data-block-type="block-hero">Hero</li>
                <li data-block-type="block-feature">Feature</li>
                <li data-block-type="block-card">Card</li>
                <li data-block-type="block-footer">Footer</li>
                <!-- Add more as needed -->
            </ul>
        </aside>

        <section class="builder-canvas">
            <div class="canvas-header">
                <h2 class="section-title">Page Layout</h2>
                <div class="canvas-buttons">
                    <button class="btn-clear">Clear Data</button>
                    <button class="btn-preview" onclick="openTab()">Preview Page</button>
                </div>
            </div>
            <div id="sortable-list" class="canvas-content">
                <!-- Blocks will be added here -->
                <div id="loading-overlay" class="loading-overlay">
                    <img src="{{ asset('assets/images/llF5iyg.gif') }}" class="loading-img">
                </div>
            </div>
        </section>
    </main>

    <script>
        var globalPageId = 1;
        var pageData;

        $(document).ready(function() {
            $("#select-page").on("change", function() {
                // Do this....
                globalPageId = $(this).val();
                fetchPageData(globalPageId);
                console.log(pageData);
                $("#sortable-list").empty();
                pageData.blocks.forEach(element => {
                    //console.log(element);
                    let blockHTML = getBlockTemplateFromServer(element);
                    $("#sortable-list").append(blockHTML);

                });
            });
        });
    </script>

    <script>
        const profileBtn = document.getElementById('profileBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');

        profileBtn.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        function fetchPageData(page) {
            $.ajax({
                type: "GET",
                url: "{{ route('pageData', ['id' => ':id']) }}".replace(':id', page),
                async: false,
                success: function(response) {
                    pageData = response;
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function createBlock(blockData) {
            console.log("blockData", blockData);
            $.ajax({
                type: "POST",
                url: "{{ route('createBlock') }}",
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    pageId: globalPageId,
                    blockData: blockData
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
    </script>

@endsection
