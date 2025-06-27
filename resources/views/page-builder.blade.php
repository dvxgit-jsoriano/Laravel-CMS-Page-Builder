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
                    <a href="#">Settings</a>
                    <a href="#">Logout</a>
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
            <div>
                <textarea id="txtDisplay" rows="4"
                    style="width: 100%; border: solid 1px #CCCCCC; margin-top: 1rem; font-size:9px;"></textarea>
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

    <div id="grouped-scripts" style="display:none;">
        @include('layouts.template-scripts')
    </div>

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
            loadBlocksPerTemplate(globalTemplateName);

            $("#select-page").on("change", function() {
                // Do this to refresh the page layout....
                globalPageId = $(this).val();

                refreshPageLayout();
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
                let blockHTML = getBlockTemplateFromServer(globalTemplateName, element);
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
                        let blockHTML = getBlockTemplateFromServer(globalTemplateName, element);
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

            $('#txtDisplay').val(JSON.stringify(fields, null, 2));

            populateModalBody(fields, targetId);
            $('#' + targetId).show(); // or your custom modal open logic
        }

        function openDeleteModal(triggerEl) {
            let targetId = $(triggerEl).data('target');
            let blockId = $(triggerEl).data('id');

            globalBlockId = blockId;

            $('#' + targetId).show(); // or your custom modal open logic
        }

        function populateModalBody(data, targetId) {
            const body = $('#' + targetId + ' .modal-body');

            const renderFieldInput = (field, groupId = '', position = '') => {
                const isHtml = field.field_type === 'html';

                const baseAttrs = `
                        id="${field.id}"
                        class="modal-input ${isHtml ? ' dynamic-editor' : ''}"
                        data-field-type="group-item"
                        data-group-id="${groupId}"
                        data-position="${position}"
                        data-field-name="${field.field_name}"
                    `;

                switch (field.field_type) {
                    case 'text':
                        return `<input type="text" value="${field.field_value}" ${baseAttrs} />`;
                    case 'textarea':
                        return `<textarea ${baseAttrs}>${field.field_value}</textarea>`;
                    case 'html':
                        return `<div ${baseAttrs}>${field.field_value}</div>`;
                    case 'file':
                        return `<input type="file" value="${field.field_value}" ${baseAttrs} />`;
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
                                <input data-id="${field.id}" type="text" data-field-key="${field.field_key}" data-field-type="${field.field_type}" value="${field.field_value}" class="modal-input" />
                            </div>`;
                        break;
                    case 'file':
                        htmlElement = `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <input data-id="${field.id}" type="file" data-field-key="${field.field_key}" data-field-type="${field.field_type}" value="${field.field_value}" class="modal-input" />
                            </div>`;
                        break;
                    case 'html':
                        htmlElement = `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <div data-id="${field.id}" data-field-key="${field.field_key}" data-field-type="${field.field_type}" class="modal-input dynamic-editor">${field.field_value}</div>
                            </div>`;
                        break;
                    case 'textarea':
                        htmlElement = `
                            <div class="modal-field-group">
                                <label for="${field.field_key}">${field.field_key}</label>
                                <textarea data-id="${field.id}" data-field-key="${field.field_key}" data-field-type="${field.field_type}" class="modal-input">${field.field_value}</textarea>
                            </div>`;
                        break;
                    case 'select':
                        htmlElement = `
                            <div class="modal-group-input">
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
                const groupId = group.group_name.toLowerCase().replace(/\s+/g, '-');
                const groupWrapper = $(`
                        <div class="modal-field-group" style="border: solid 1px #EEEEEE; padding: 1rem; background-color: #EFEFEF;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <label style="font-weight:bold;">${group.group_name}</label>
                                <button type="button" class="pb-btn-add-group-item" data-group-id="${groupId}">Add</button>
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
                    const li = $('<li style="margin-bottom: 1rem;"></li>');
                    fields.forEach(field => {
                        li.append(`
                            <div class="modal-field-group">
                                <label>${field.field_name}</label>
                                ${renderFieldInput(field, groupId, pos)}
                            </div>
                        `);
                    });

                    // Optional Delete button
                    li.append(`
                        <div style="text-align:right;">
                            <button type="button" class="pb-btn-delete-group-item" data-group-id="${groupId}" data-position="${pos}">Delete</button>
                        </div>
                    `);

                    list.append(li);
                });

                body.append(groupWrapper);
            });

            initDynamicEditors(); // initialize after HTML is rendered
        }

        function saveChanges(triggerEl) {
            // Get the modal ID from button's data-target attribute
            const targetId = $(triggerEl).data('target');
            const modal = $('#' + targetId);

            const blockId = modal.data('block-id'); // (Optional) in case you want to send which block you're editing

            const block_fields = [];
            const block_field_groups = [];

            // 1️⃣ Collect simple block_fields
            modal.find('.modal-input[data-field-key]').each(function() {
                console.log("FIELD TYPE!", $(this).data('field-type'));
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
                    const position = $(this).find('.modal-input').first().data(
                        'position'); // assume all fields in same li share same position

                    $(this).find('.modal-input').each(function() {
                        items.push({
                            id: $(this).attr('id'),
                            field_name: $(this).data('field-name'),
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
                url: '{{ route('updateBlock') }}', // <-- you will replace this with your Laravel route
                type: 'POST',
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
    </script>

    <script>
        /**
         * Initializations
         */
        loadPages();
        console.log("Initialization");
    </script>
@endsection
