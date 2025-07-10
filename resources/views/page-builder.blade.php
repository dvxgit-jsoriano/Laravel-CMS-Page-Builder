@extends('layouts.builder')

@section('content')
    <div id="grouped-styles" style="display:none;">
        @include('layouts.template-styles')
    </div>

    <header class="pb-navbar">
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
                    {{-- <a href="#">Settings</a>
                    <a href="#">Logout</a> --}}
                </div>
            </div>
        </div>
    </header>

    <main class="builder-main">
        <aside class="builder-sidebar">
            <h2 class="section-title">Site: [{{ $site->id }}] [{{ $site->name }}]</h2>
            <h2 class="section-title">Sessions: [{{ session('page_builder.site_id') }}]
                [{{ session('page_builder.template_id') }}]</h2>

            <div class="canvas-buttons">
                <button id="openPageModal" class="pb-btn-create" data-target="modalCreateNewPage"
                    onclick="openModal(this);">Create New Page</button>
            </div>
            <div class="page-dropdown">
                <h3 class="section-title">Page:</h3>
                <select name="select-page" id="select-page" class="select-primary">
                    <!-- Load options here... -->
                </select>
            </div>

            <h2 class="section-title">Components:</h2>
            <ul id="draggable-list" class="draggable-list">
                <li data-block-type="navigation">
                    <h4>Navigation</h4>
                    <img src="https://www.wickedblocks.dev/screenshots/original/header1.png">
                </li>
                <li data-block-type="hero">Hero</li>
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
                    <button class="pb-btn-clear">Clear Data</button>
                    <button class="pb-btn-preview" onclick="openTab()">Preview Page</button>
                </div>
            </div>
            <div id="sortable-list" class="canvas-content">
                <!-- Blocks will be added here -->
                <div id="loading-overlay" class="loading-overlay">
                    <img src="{{ asset('assets/images/llF5iyg.gif') }}" class="loading-img">
                </div>
            </div>
            {{-- <div>
                <textarea id="txtDisplay" rows="4"
                    style="width: 100%; border: solid 1px #CCCCCC; margin-top: 1rem; font-size:9px;"></textarea>
            </div> --}}
        </section>
    </main>

    <!-- ********************************************************************* -->
    <!-- Create your modals here -->
    <!-- ********************************************************************* -->
    <!-- Create New Site modal -->
    <div id="modalCreateNewSite" class="modal-overlay">
        <div class="modal-box">
            <span class="modal-close-x" data-target="modalCreateNewSite" onclick="closeModal(this)">&times;</span>
            <h2 class="section-title">Create a new site</h2>

            <div class="modal-body">
                <label for="siteName">Site
                    Name</label>
                <input type="text" id="siteName" name="siteName" class="modal-input"
                    placeholder="Enter a name of a site">
            </div>

            <div class="canvas-buttons">
                <button class="pb-btn-primary" data-target="modalCreateNewSite" onclick="createSite(this)">Create</button>
                <button class="modal-close-btn" data-target="modalCreateNewSite" onclick="closeModal(this)">Close</button>
            </div>
        </div>
    </div>

    <!-- Create New Page modal -->
    <div id="modalCreateNewPage" class="modal-overlay">
        <div class="modal-box">
            <span class="modal-close-x" data-target="modalCreateNewPage" onclick="closeModal(this)">&times;</span>
            <h2 class="section-title">Create a new page</h2>

            <div class="modal-body">
                <label for="pageName">Page
                    Name</label>
                <input type="text" id="pageName" name="pageName" class="modal-input"
                    placeholder="Enter a name of the page">
            </div>

            <div class="canvas-buttons">
                <button class="pb-btn-primary" data-target="modalCreateNewPage" onclick="createPage(this)">Create</button>
                <button class="modal-close-btn" data-target="modalCreateNewPage" onclick="closeModal(this)">Close</button>
            </div>
        </div>
    </div>

    <!-- Dynamic Edit Block modal -->
    <div id="modalEditBlock" class="modal-overlay">
        <div class="modal-box" style="width: 700px;">
            <span class="modal-close-x" data-target="modalEditBlock" onclick="closeModal(this)">&times;</span>
            <h2 class="section-title">Edit the page block</h2>

            <div class="modal-body">
                <!-- Dynamic content will be inserted here -->
            </div>

            <div class="canvas-buttons">
                <button class="pb-btn-primary" data-target="modalEditBlock" onclick="saveChanges(this)">Save
                    Changes</button>
                <button class="modal-close-btn" data-target="modalEditBlock" onclick="closeModal(this)">Close</button>
            </div>
        </div>
    </div>

    <div id="modalDeleteBlock" class="modal-overlay">
        <div class="modal-box">
            <span class="modal-close-x" data-target="modalDeleteBlock" onclick="closeModal(this)">&times;</span>
            <h2 class="section-title">Confirmation</h2>

            <div class="modal-body">
                <h4 class="section-body-text">Are you sure you want to delete this block?</h4>
            </div>

            <div class="canvas-buttons">
                <button class="pb-btn-danger" data-target="modalDeleteBlock" onclick="deleteBlock(this)">Yes</button>
                <button class="modal-close-btn" data-target="modalDeleteBlock" onclick="closeModal(this)">No</button>
            </div>
        </div>
    </div>

    <!-- Modal overlay -->
    <div id="modalAssetLibrary" class="modal-overlay" style="z-index: 1000002;">
        <div class="modal-box" style="width: 80%;">
            <span class="modal-close-x" data-target="modalAssetLibrary" onclick="closeModal(this)">&times;</span>
            <h2 class="section-title">Asset Library</h2>

            <div class="modal-body">
                <div id="galleryGrid" class="gallery-grid">
                    <!-- images will be dynamically loaded here -->
                </div>
                <div id="galleryLoader" class="gallery-loader">
                    <em>Loading...</em>
                </div>
            </div>
            <div class="canvas-buttons">
                <button class="pb-btn-danger" data-target="modalAssetLibrary" onclick="selectAsset(this)">Select</button>
                <button class="modal-close-btn" data-target="modalAssetLibrary" onclick="closeModal(this)">Close</button>
            </div>
        </div>
    </div>


    <div id="grouped-scripts" style="display:none;">
        @include('layouts.template-scripts')
    </div>

    <!-- Include BlockListLoader.js -->
    <script src="assets/js/BlockListLoader.js"></script>

    <script>
        /**
         * Global Variables
         */
        var globalSite = @json($site->id);
        var globalSiteId = @json($site->id);
        var globalTemplates;
        var globalTemplateId = @json($template->id) ?? 1;
        var globalTemplateName = @json($template->name) ?? '';
        var globalPageId;
        var globalBlockId;
        var pageData;
        var previousTemplateId;
        var selectedAssetSrc = null;
        var currentTargetHiddenInput = null;
        var currentTargetHiddenGroupInput = null;

        var currentGalleryPage = 1;
        var galleryLoading = false;
        var galleryHasMore = true;

        function getSiteInfo() {
            siteId = $("#select-site").val();
            $.ajax({
                type: "GET",
                url: "{{ route('getSiteInfo', ['siteId' => ':siteId']) }}".replace(':siteId', siteId),
                async: false,
                success: function(response) {
                    globalTemplateId = response.template_id;
                    $('#select-template').val(globalTemplateId);
                }
            });

            loadPages();
        }

        $(document).ready(function() {
            globalSite = {{ $site->id }};
            globalSiteId = {{ $site->id }};

            // Call function loadBlocksPerTemplate(), this loads the components or blocks of the selected template.
            let blockListLoader = new BlockListLoader();
            blockListLoader.loadBlocksPerTemplate(globalTemplateName);

            $("#select-page").on("change", function() {
                // Do this to refresh the page layout....
                globalPageId = $(this).val();

                refreshPageLayout();
            });
        });
    </script>

    <script>
        /** For the profile button and dropdown */
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
    </script>

    <script>
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

        function loadPages() {
            $.ajax({
                type: "GET",
                url: "{{ route('getPages', ['siteId' => ':siteId']) }}".replace(':siteId', globalSite),
                data: {
                    templateId: globalTemplateId
                },
                success: function(response) {
                    $('#select-page').empty();
                    $('#select-page').append(
                        `<option selected disabled>--Select Page--</option>`
                    );

                    $.each(response, function(index, el) {
                        $("#select-page").append(
                            `<option value="${el.id}">${el.name}</option>`
                        );
                    });
                }
            });
        }

        function refreshPageLayout() {
            fetchPageData(globalPageId);
            $('#sortable-list').children().not('#loading-overlay').remove();
            pageData.blocks.forEach(element => {
                let blockListLoader = new BlockListLoader();
                let blockHTML = blockListLoader.getBlockTemplateFromServer(globalTemplateName, element);
                $("#sortable-list").append(blockHTML);
            });
        }

        function initDynamicEditors() {
            $('.dynamic-editor').each(function() {
                if (!$(this).hasClass('summernote-initialized')) {
                    $(this).summernote({
                        height: 200,
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline']],
                            ['font', ['fontname', 'color']],
                            ['para', ['ul', 'ol']],
                            ['insert', ['link', 'picture', 'table']],
                            //['view', ['codeview']]
                        ],
                        callbacks: {
                            onPaste: function(e) {
                                // Avoid default paste behavior
                                e.preventDefault();

                                // Get plain text from clipboard
                                const clipboardData = (e.originalEvent || e).clipboardData || window
                                    .clipboardData;
                                const text = clipboardData.getData('text/plain');

                                // Insert plain text only
                                document.execCommand('insertText', false, text);
                            }
                        }
                    }).addClass('summernote-initialized');
                }
            });
        }

        function createPage(triggerEl) {
            let pageName = $("#pageName").val();

            console.log("Creating new page...", pageName);

            $.ajax({
                type: "POST",
                url: "{{ route('createPage') }}",
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    siteId: globalSite,
                    pageName: pageName,
                    templateId: globalTemplateId,
                },
                success: function(response) {
                    //console.log(response);

                    loadPages();

                    closeModal(triggerEl);
                }
            });
        }

        function createBlock(type) {
            $.ajax({
                type: "POST",
                url: "{{ route('createBlock') }}",
                async: false,
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    templateName: globalTemplateName,
                    pageId: globalPageId,
                    type: type
                },
                success: function(response) {
                    //console.log(response);
                    result = response;
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return result;
        }

        function deleteBlock(triggerEl) {
            //console.log("Block ID: ", globalBlockId);

            $.ajax({
                type: "DELETE",
                url: "{{ route('deleteBlock') }}",
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    blockId: globalBlockId
                },
                success: function(response) {
                    /* console.log("Successfully deleted!!!!");
                    console.log(response); */

                    // Refresh the page layout contents
                    fetchPageData(globalPageId);
                    // Empty the page layout excluding the loading-overlay
                    $('#sortable-list').children().not('#loading-overlay').remove();
                    pageData.blocks.forEach(element => {
                        let blockListLoader = new BlockListLoader();
                        let blockHTML = blockListLoader.getBlockTemplateFromServer(globalTemplateName,
                            element);
                        $("#sortable-list").append(blockHTML);
                    });

                    closeModal(triggerEl);
                }
            });
        }

        function openEditModal(triggerEl) {
            let targetId = $(triggerEl).data('target');
            let blockId = $(triggerEl).data('id');

            // Collect block details like below
            let fields = [];

            // Ajax to route
            $.ajax({
                type: "GET",
                url: "{{ route('getBlockSet') }}",
                data: {
                    blockId: blockId
                },
                async: false,
                success: function(response) {
                    fields = response;
                }
            });

            //$('#txtDisplay').val(JSON.stringify(fields, null, 2));

            populateModalBody(fields, targetId);
            $('#' + targetId).show(); // or your custom modal open logic
        }

        function openDeleteModal(triggerEl) {
            let targetId = $(triggerEl).data('target');
            let blockId = $(triggerEl).data('id');

            globalBlockId = blockId;

            $('#' + targetId).show(); // or your custom modal open logic
        }

        function openAssetLibraryModal(triggerEl) {
            let targetId = $(triggerEl).data('target');
            let templateId = globalTemplateId;

            let fieldId = null;
            if ($(triggerEl).data('hidden-input'))
                fieldId = $(triggerEl).data('hidden-input');
            if ($(triggerEl).data('hidden-group-input'))
                fieldId = $(triggerEl).data('hidden-group-input');

            currentTargetHiddenInput = `hidden-input-${fieldId}`;
            currentTargetHiddenGroupInput = `hidden-group-input-${fieldId}`;

            // reset gallery state
            galleryHasMore = true;
            galleryLoading = false;
            $('#galleryGrid').empty(); // clear old items
            $('#galleryLoader').hide(); // hide loader

            // load page 1 fresh
            loadAssetLibraryPage(1);

            $('#' + targetId).show(); // or your custom modal open logic
        }

        function populateModalBody(data, targetId) {
            const body = $('#' + targetId + ' .modal-body');

            const renderFieldInput = (field, groupId = '', position = '') => {
                const isHtml = field.field_type === 'html';

                const baseAttrs = `
                        id="${field.id}"
                        data-id="${field.id}"
                        class="modal-input ${isHtml ? ' dynamic-editor' : ''}"
                        data-group="true"
                        data-group-id="${groupId}"
                        data-position="${position}"
                        data-field-key="${field.field_key}"
                    `;

                switch (field.field_type) {
                    case 'text':
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <input type="text" value="${field.field_value}" ${baseAttrs} />
                            </div>
                        `;
                    case 'textarea':
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <textarea ${baseAttrs}>${field.field_value}</textarea>
                            </div>
                        `;
                    case 'html':
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <div ${baseAttrs}>${field.field_value}</div>
                            </div>
                        `;
                    case 'file':
                        //return `<input type="file" value="${field.field_value}" ${baseAttrs} />`;
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <div style="display:flex; gap:10px;">
                                    <button type="button" class="pb-btn-add-group-item ms-2" style="flex:1; background:#007bff; color:white;"
                                        data-target="modalAssetLibrary"
                                        data-hidden-group-input="${field.id}"
                                        onclick="openAssetLibraryModal(this);">
                                        Select From Gallery
                                    </button>
                                    <input id="hidden-group-input-${field.id}" data-id="${field.id}" type="hidden" data-field-key="${field.field_key}" value="${field.field_value}" data-position="${position}" class="modal-input" data-group="true" />
                                    <input type="file" class="modal-input" style="flex:2;"/>
                                    <button type="button" class="pb-btn-add-group-item"
                                        style="flex:1; background:#28a745; color:white;"
                                        data-field-id="${field.id}"
                                        onclick="uploadAssetLibraryFile(this, ${field.id})">
                                        Upload
                                    </button>
                                </div>
                            </div>
                        `;
                    case 'select':
                        return `<select ${baseAttrs}>
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>`;
                    default:
                        return '';
                }
            };

            body.empty(); // Clear existing content

            // 1. Render basic block_fields
            data.block_fields.forEach(field => {
                let htmlElement;

                switch (field.field_type) {
                    case 'text':
                        htmlElement = `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <input data-id="${field.id}" type="text" data-single="true" data-field-key="${field.field_key}" data-field-type="${field.field_type}" value="${field.field_value}" class="modal-input" />
                            </div>`;
                        break;
                    case 'file':
                        htmlElement = `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <div style="display:flex; gap:10px;">
                                    <button type="button" class="pb-btn-add-group-item ms-2" style="flex:1; background:#007bff; color:white;"
                                        data-target="modalAssetLibrary"
                                        data-hidden-input="${field.id}"
                                        onclick="openAssetLibraryModal(this);">
                                        Select From Gallery
                                    </button>
                                    <input id="hidden-input-${field.id}" data-id="${field.id}" type="hidden" data-field-key="${field.field_key}" value="${field.field_value}" class="modal-input" data-single="true" />
                                    <input type="file" class="modal-input" style="flex:2;"
                                    />
                                    <button type="button" class="pb-btn-add-group-item"
                                        style="flex:1; background:#28a745; color:white;"
                                        data-field-id="${field.id}"
                                        onclick="uploadAssetLibraryFile(this, ${field.id})">
                                        Upload
                                    </button>
                                </div>
                            </div>`;
                        break;
                    case 'html':
                        htmlElement = `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <div data-id="${field.id}" data-single="true" data-field-key="${field.field_key}" data-field-type="${field.field_type}" class="modal-input dynamic-editor">${field.field_value}</div>
                            </div>`;
                        break;
                    case 'textarea':
                        htmlElement = `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <textarea data-id="${field.id}" data-single="true" data-field-key="${field.field_key}" data-field-type="${field.field_type}" class="modal-input">${field.field_value}</textarea>
                            </div>`;
                        break;
                    case 'select':
                        htmlElement = `
                            <div class="modal-group-input" data-single="true">
                                <select class="modal-input">
                                    <option>Test 1</option>
                                    <option>Test 2</option>
                                </select>
                            </div>
                        `;
                        break;

                    default:
                        break;
                }

                body.append(htmlElement);
            });

            // 2. Render grouped fields
            data.block_field_groups.forEach(group => {
                const blockFieldGroupId = group.id;
                const groupId = group.group_name.toLowerCase().replace(/\s+/g, '-');
                const groupWrapper = $(`
                        <div class="modal-field-group" style="border: solid 1px #EEEEEE; padding: 1rem; background-color: #EFEFEF;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <label style="font-weight:bold;">${group.group_name}</label>
                                <button type="button" class="pb-btn-add-group-item" data-block-field-group-id="${blockFieldGroupId}" data-group-id="${groupId}" onclick="createNewBlockFieldGroupItem(this);">Add</button>
                            </div>
                            <ul class="group-field-list" data-group-id="${groupId}" style="margin-top: 1rem;"></ul>
                        </div>
                    `);

                const list = groupWrapper.find('ul');

                // Group by position
                const groupedItems = {};
                group.items.forEach(item => {
                    if (!groupedItems[item.position]) {
                        groupedItems[item.position] = [];
                    }
                    groupedItems[item.position].push(item);
                });

                Object.entries(groupedItems).forEach(([pos, fields]) => {
                    // Set the blockFieldGroupId and position for each li item. This is for delete purpose.
                    const li = $(
                        `<li style="margin-bottom: 1rem;" data-group-id="${blockFieldGroupId}" data-position="${pos}"></li>`
                    );
                    fields.forEach(field => {
                        li.append(`
                            <div class="modal-field-group" data-block-field-group-item-id="${field.id}">
                                ${renderFieldInput(field, groupId, pos)}
                            </div>
                        `);
                    });

                    // Optional Delete button
                    li.append(`
                        <div style="text-align:right;">
                            <button type="button" class="pb-btn-delete-group-item" data-group-id="${blockFieldGroupId}" data-position="${pos}" onclick="deleteGroupItems(this);">Delete</button>
                        </div>
                    `);

                    list.append(li);
                });

                body.append(groupWrapper);
            });

            initDynamicEditors(); // initialize after HTML is rendered
        }

        function loadAssetLibraryPage(page = 1) {
            if (galleryLoading) return; // no more hasMore needed for button

            galleryLoading = true;
            $('#galleryLoader').show();

            $.ajax({
                url: '/get-asset-library',
                method: 'GET',
                data: {
                    template_id: globalTemplateId,
                    page: page
                },
                success: function(response) {
                    const assets = response.data;

                    if (assets.length === 0) {
                        // no assets found at all
                        $('#galleryGrid').append(`
                            <div style="grid-column: 1/-1; text-align:center; padding:20px;">
                                No images found.
                            </div>
                        `);
                    } else {
                        assets.forEach(asset => {
                            $('#galleryGrid').append(`
                                <div class="gallery-item"
                                    style="border:1px solid #ddd; padding:5px; cursor:pointer;"
                                    data-src="${asset.src}"
                                    onclick="selectGalleryItem(this)">
                                    <img src="${asset.src}" style="width:100%; border-radius:5px;">
                                </div>
                            `);
                        });
                    }

                    // remove existing Load More button if any
                    $('#galleryGrid .gallery-load-more').remove();

                    if (response.current_page < response.last_page) {
                        // there is more to load
                        $('#galleryGrid').append(`
                            <div class="gallery-load-more" style="grid-column: 1/-1; text-align:center; margin:10px 0;">
                                <button type="button" style="padding:8px 16px; background:#007bff; color:#fff; border:none; border-radius:5px;"
                                    onclick="loadAssetLibraryPage(${response.current_page + 1})">
                                    Load More
                                </button>
                            </div>
                        `);
                    }
                },
                error: function(err) {
                    console.error(err);
                },
                complete: function() {
                    galleryLoading = false;
                    $('#galleryLoader').hide();
                }
            });
        }

        function uploadAssetLibraryFile(triggerEl, fieldId) {
            const container = $(triggerEl).closest('.modal-field-group');
            const fileInput = container.find('input[type="file"]')[0];

            console.log("Uploading file for field ID:", fieldId);
            console.log("File input element:", fileInput);

            if (!fileInput || !fileInput.files.length) {
                alert("Please select a file to upload.");
                return;
            }

            const file = fileInput.files[0];

            let formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('template_id', globalTemplateId);
            formData.append('file', file);

            $.ajax({
                url: '/upload-asset-library',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert("Upload successful!");
                        // update the hidden input with the returned image path
                        $(`#hidden-input-${fieldId}`).val(response.asset.src);
                    } else {
                        alert("Upload failed.");
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert("Something went wrong while uploading.");
                }
            });
        }

        function selectGalleryItem(el) {
            // remove outline from other items
            $('#galleryGrid .gallery-item').css('outline', 'none');

            // add highlight to selected
            $(el).css('outline', '3px solid #007bff');

            // store globally
            selectedAssetSrc = $(el).data('src');

            console.log('Selected asset:', selectedAssetSrc);
        }

        function selectAsset(triggerEl) {
            if (!selectedAssetSrc) {
                alert("Please select an image first.");
                return;
            }

            if (!currentTargetHiddenInput) {
                console.error('No target hidden input set.');
                return;
            }

            if (!currentTargetHiddenGroupInput) {
                console.error('No target hidden input set.');
                return;
            }

            $('#' + currentTargetHiddenInput).val(selectedAssetSrc);
            $('#' + currentTargetHiddenGroupInput).val(selectedAssetSrc);

            console.log('Saved to hidden input:', currentTargetHiddenInput, selectedAssetSrc);
            console.log('Saved to hidden group input:', currentTargetHiddenGroupInput, selectedAssetSrc);

            closeModal(triggerEl);
        }

        function saveChanges(triggerEl) {
            // Get the modal ID from button's data-target attribute
            const targetId = $(triggerEl).data('target');
            const modal = $('#' + targetId);

            const blockId = modal.data('block-id'); // (Optional) in case you want to send which block you're editing

            const block_fields = [];
            const block_field_groups = [];

            // 1️⃣ Collect simple block_fields
            modal.find('.modal-input[data-single]').each(function() {
                const field = {
                    id: $(this).data('id'),
                    field_key: $(this).data('field-key'),
                    field_value: $(this).data('field-type') === 'html' ? $(this).summernote('code') : $(this)
                        .val()
                };
                block_fields.push(field);
            });

            // 2️⃣ Collect grouped fields
            modal.find('.group-field-list').each(function() {
                const groupId = $(this).data('group-id');
                const group_name = groupId.replace(/-/g, ' ').replace(/\b\w/g, c => c
                    .toUpperCase()); // convert back if needed

                const items = [];

                $(this).find('li').each(function() {
                    const position = $(this).find('.modal-input[data-group]').first().data(
                        'position'); // assume all fields in same li share same position

                    $(this).find('.modal-input[data-group]').each(function() {
                        items.push({
                            id: $(this).data('id'),
                            field_key: $(this).data('field-key'),
                            field_value: $(this).val(),
                            position: position
                        });
                    });
                });

                block_field_groups.push({
                    group_name: group_name,
                    items: items
                });
            });

            // 🔥 Prepare the final payload
            const payload = {
                block_fields: block_fields,
                block_field_groups: block_field_groups
            };

            console.log("Saving Payload: ", payload); // For debugging

            // 3️⃣ Send AJAX request to Laravel
            $.ajax({
                url: "{{ route('updateBlock') }}", // <-- you will replace this with your Laravel route
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    payload: JSON.stringify(payload)
                },
                success: function(response) {
                    console.log(response);
                    refreshPageLayout();
                    console.log("Changes saved successfully!");
                },
                error: function(response) {
                    console.error(response.responseJSON.message ?? "There is an error found.");
                }
            });

            closeModal(triggerEl);
        }

        function createNewBlockFieldGroupItem(el) {
            const renderFieldInput = (field, groupId = '', position = '') => {
                const isHtml = field.field_type === 'html';

                const baseAttrs = `
                        id="${field.id}"
                        data-id="${field.id}"
                        class="modal-input ${isHtml ? ' dynamic-editor' : ''}"
                        data-group="true"
                        data-group-id="${groupId}"
                        data-position="${position}"
                        data-field-key="${field.field_key}"
                    `;

                switch (field.field_type) {
                    case 'text':
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <input type="text" value="${field.field_value}" ${baseAttrs} />
                            </div>
                        `;
                    case 'textarea':
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <textarea ${baseAttrs}>${field.field_value}</textarea>
                            </div>
                        `;
                    case 'html':
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <div ${baseAttrs}>${field.field_value}</div>
                            </div>
                        `;
                    case 'file':
                        //return `<input type="file" value="${field.field_value}" ${baseAttrs} />`;
                        return `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <div style="display:flex; gap:10px;">
                                    <button type="button" class="pb-btn-add-group-item ms-2" style="flex:1; background:#007bff; color:white;"
                                        data-target="modalAssetLibrary"
                                        data-hidden-group-input="${field.id}"
                                        onclick="openAssetLibraryModal(this);">
                                        Select From Gallery
                                    </button>
                                    <input id="hidden-group-input-${field.id}" data-id="${field.id}" type="hidden" data-field-key="${field.field_key}" value="${field.field_value}" data-position="${position}" class="modal-input" data-group="true" />
                                    <input type="file" class="modal-input" style="flex:2;"/>
                                    <button type="button" class="pb-btn-add-group-item"
                                        style="flex:1; background:#28a745; color:white;"
                                        data-field-id="${field.id}"
                                        onclick="uploadAssetLibraryFile(this, ${field.id})">
                                        Upload
                                    </button>
                                </div>
                            </div>
                        `;
                    case 'select':
                        return `<select ${baseAttrs}>
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>`;
                    default:
                        return '';
                }
            };

            const $btn = $(el);
            const blockFieldGroupId = $btn.data('block-field-group-id'); // numeric ID
            const groupId = $btn.data('group-id'); // slugified name (used in data-group-id)
            const $list = $(`.group-field-list[data-group-id="${groupId}"]`);

            $.ajax({
                type: "POST",
                url: "{{ route('createBlockFieldGroupItem') }}",
                async: false,
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    groupId: blockFieldGroupId,
                },
                success: function(response) {
                    console.log(response);

                    if (!Array.isArray(response) || response.length === 0) return;

                    const position = response[0].position;
                    const $li = $('<li style="margin-bottom: 1rem;"></li>');

                    response.forEach(field => {
                        const inputHtml = renderFieldInput(field, groupId, position);
                        $li.append(`<div class="modal-field-group">${inputHtml}</div>`);
                    });

                    $li.append(`
                        <div style="text-align:right;">
                            <button type="button"
                                class="pb-btn-delete-group-item"
                                data-group-id="${groupId}"
                                data-position="${position}">
                                Delete
                            </button>
                        </div>
                    `);

                    $list.append($li);

                    // Optional: reinitialize editors if HTML fields were added
                    initDynamicEditors();
                },
                error: function(response) {
                    console.error('Error creating group item:', response);
                    alert('Failed to add group item.');
                }
            });
        }

        function deleteGroupItems(el) {
            const groupId = $(el).data('group-id');
            const position = $(el).data('position');
            //console.log("Deleting group items...", groupId, position);

            const $allItemsInGroup = $(`.pb-btn-delete-group-item[data-group-id="${groupId}"]`);
            const uniquePositions = [...new Set($allItemsInGroup.map((_, el) => $(el).data('position')).get())];

            // Validate deletion if there is only 1 item left in the group.
            if (uniquePositions.length <= 1) {
                alert("You must keep at least one group item.");
                return;
            }

            $.ajax({
                type: "DELETE",
                url: "{{ route('deleteBlockFieldGroupItems') }}",
                async: false,
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token added here
                    groupId: groupId,
                    position: position
                },
                success: function(response) {
                    // Find and remove the corresponding <li> with data-group-id and data-position.
                    $(`li[data-group-id="${groupId}"][data-position="${position}"]`).remove();
                },
                error: function(response) {
                    alert("There is an error found while deleting the group items.");
                }
            });
        }
    </script>

    <script>
        /**
         * Initializations
         */
        loadPages();
        console.log("Initialization");
    </script>
@endsection
