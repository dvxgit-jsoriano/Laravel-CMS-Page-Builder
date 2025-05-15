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
            <h2 class="section-title">Components</h2>
            {{-- <h2 class="section-title">Site: [{{ $site->id }}] [{{ $site->name }}]</h2> --}}
            <div class="canvas-buttons">
                <button id="openSiteModal" class="btn-create" data-target="modalCreateNewSite"
                    onclick="openModal(this)">Create New Site</button>
            </div>

            <div class="page-dropdown">
                <h3 class="section-title">Site:</h3>
                <select name="select-site" id="select-site" class="select-primary">
                    @if (!empty($pages) && count($pages) > 0)
                        @foreach ($pages as $page)
                            <option value="{{ $page->id ?? '' }}">{{ $page->name ?? '' }}</option>
                        @endforeach
                    @else
                        <option disabled>No pages available</option>
                    @endif
                </select>
            </div>
            {{-- <h2 class="section-title">Template: [{{ $template->id }}] [{{ $template->name }}]</h2> --}}
            <div class="page-dropdown">
                <h3 class="section-title">Template:</h3>
                <select name="select-template" id="select-template" class="select-primary">
                    <option selected disabled>--Select Template--</option>
                    @if (!empty($pages) && count($pages) > 0)
                        @foreach ($pages as $page)
                            <option value="{{ $page->id ?? '' }}">{{ $page->name ?? '' }}</option>
                        @endforeach
                    @else
                        <option disabled>No pages available</option>
                    @endif
                </select>
            </div>
            <div class="canvas-buttons">
                <button id="openPageModal" class="btn-create">Create New Page</button>
            </div>
            <div class="page-dropdown">
                <h3 class="section-title">Page:</h3>
                <select name="select-page" id="select-page" class="select-primary">
                    @if (!empty($pages) && count($pages) > 0)
                        @foreach ($pages as $page)
                            <option value="{{ $page->id ?? '' }}">{{ $page->name ?? '' }}</option>
                        @endforeach
                    @else
                        <option disabled>No pages available</option>
                    @endif
                </select>
            </div>

            <!-- Create New Page modal -->
            <div class="modal-overlay" id="createNewPageModal">
                <div class="modal-box">
                    <span class="modal-close-x" id="modalCloseX">&times;</span>
                    <h2>Create a new page</h2>

                    <div class="modal-body">
                        <label for="pageName">Page
                            Name</label>
                        <input type="text" id="pageName" name="pageName" class="modal-input"
                            placeholder="Enter a name of a page">
                    </div>

                    <div class="canvas-buttons">
                        <button id="modalCreatePage" class="btn-primary" onclick="createPage()">Create</button>
                        <button id="modalCloseBtn" class="modal-close-btn">Close</button>
                    </div>
                </div>
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

    <!-- ********************************************************************* -->
    <!-- Create your modals here -->
    <!-- ********************************************************************* -->
    <!-- Create New Site modal -->
    <div id="modalCreateNewSite" class="modal-overlay">
        <div class="modal-box">
            <span class="modal-close-x" data-target="modalCreateNewSite" onclick="closeModal(this)">&times;</span>
            <h2>Create a new site</h2>

            <div class="modal-body">
                <label for="siteName">Site
                    Name</label>
                <input type="text" id="siteName" name="siteName" class="modal-input"
                    placeholder="Enter a name of a site">
            </div>

            <div class="canvas-buttons">
                <button class="btn-primary" onclick="createSite()">Create</button>
                <button class="modal-close-btn" data-target="modalCreateNewSite"
                    onclick="closeModal(this)">Close</button>
            </div>
        </div>
    </div>


    <script>
        /**
         * Global Variables
         */
        var globalSite;
        var globalTemplateId = {{ $template->id ?? 1 }};
        var globalPageId;
        var pageData;

        function fetchSites() {
            $.ajax({
                type: "GET",
                url: "{{ route('getSites') }}",
                async: false,
                success: function(response) {
                    loadSites(response);
                },
                error: function(response) {
                    console.error(response);
                },
                complete: function() {}
            });
        }

        $(document).ready(function() {
            $("#select-site").on("change", function() {
                globalSite = $(this).val();
                console.log("globalSite", globalSite);
            });

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

        function loadSites(data) {
            let sel = false;
            $("#select-site").empty();
            $.each(data, function(index, el) {
                if (el.active) {
                    sel = "selected";
                    globalSite = el.id; // Set the globalSite to the active site.
                } else {
                    sel = "";
                }
                $("#select-site").append(
                    `<option value="${el.id}" ${sel}>${el.name}</option>`
                );
            });
        }

        function createSite() {
            let siteName = $('#siteName').val();

            console.log("Createing new site...", siteName);

            $.ajax({
                type: "POST",
                url: "{{ route('createSite') }}",
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    siteName: siteName,
                },
                success: function(response) {
                    console.log(response);
                    loadSites(response)
                }
            });

        }

        function createPage() {
            let pageName = $("#pageName").val();

            console.log("Creating new page...", pageName);

            $.ajax({
                type: "POST",
                url: "url",
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    pageName: pageName,
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }

        function createBlock(blockData) {
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

    <script>
        /**
         * Initializations
         */
        fetchSites();
        console.log("Initialization");
        console.log("Site", globalSite);
    </script>

@endsection
