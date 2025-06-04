function loadBlocksPerTemplate(templateName) {
    $("#draggable-list").empty();
    switch (templateName) {
        case "Default Template":
            $("#draggable-list").append(`
                <li data-block-type="navigation">
                    <h4>Navigation</h4>
                    <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg">
                </li>
            `);
            $("#draggable-list").append(`
                <li data-block-type="hero">
                    <h4>Hero</h4>
                    <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg">
                </li>
            `);
            break;

        case "Wicked Blocks":
            $("#draggable-list").append(`
                <li data-block-type="Navigation Header">
                    <h4>Navigation</h4>
                    <img src="https://www.wickedblocks.dev/screenshots/original/header3.png">
                </li>
            `);
            $("#draggable-list").append(`
                <li data-block-type="navigation">Navigation</li>
            `);

            $("#draggable-list").append(`
                <li data-block-type="hero">Hero</li>
            `);
            break;

        default:
    }
}


function getBlockTemplateFromServer(blockData) {

    // This is an arrow function used as a helper to find the field key and return the field value.
    const getFieldValue = (key) => {
        const field = blockData.block_fields.find(f => f.field_key === key);
        return field ? escapeHtml(field.field_value) : '';
    };

    const renderActionButtons = (blockId) => `
        <button class="absolute top-2 left-2 bg-red-800 bg-opacity-50 text-white text-xs px-3 py-1 rounded hover:bg-opacity-70 transition hidden group-hover:block edit-btn"
            data-target="modalEditBlock" data-id="${blockId}" onclick="openEditModal(this)">Edit</button>
        <button class="absolute top-2 left-14 bg-red-800 bg-opacity-50 text-white text-xs px-3 py-1 rounded hover:bg-opacity-70 transition hidden group-hover:block edit-btn"
            data-target="modalDeleteBlock" data-id="${blockId}" onclick="openDeleteModal(this)">Delete</button>
    `;

    // Check the type of block and return HTML accordingly
    switch (blockData.type) {
        case 'hero':
            return `
                <section class="block group relative" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                    ${renderActionButtons(blockData.id)}
                    <div class="p-6 bg-blue-100 rounded shadow">
                        <h1 class="text-2xl font-bold mb-2">${getFieldValue('Title')}</h1>
                        <p class="text-gray-700">${getFieldValue('Description')}</p>
                    </div>
                </section>
            `;
        case 'banner':
            return `
                <section class="block group relative" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                    ${renderActionButtons(blockData.id)}
                    <div class="p-4 bg-yellow-100 rounded shadow text-center">
                        <p class="font-semibold">${escapeHtml(blockData.description)}</p>
                    </div>
                </section>
            `;
        case 'navigation':
            const centerItems = extractGroupedItems(blockData, 'Center Links');

            const centerLinks = centerItems.map(item => `
                <a href="${item.URL}" class="p-4 underline-none text-gray-800 hover:text-gray-700">${item.Title}</a>
            `).join('');

            return `
                <section class="block group relative" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                    ${renderActionButtons(blockData.id)}
                    <div class="p-4 flex justify-between items-center bg-green-100 rounded shadow">
                        <div class="flex items-center">
                            <img src="${getFieldValue('Logo Image URL')}" class="h-8" alt="Logo">
                            <span class="ms-2 font-medium">${getFieldValue('Logo Title')}</span>
                        </div>
                        <div class="flex flex-row">
                            ${centerLinks}
                        </div>
                        <div>
                            <a href="${getFieldValue('Profile URL')}" class="p-4 underline-none text-gray-800 hover:text-gray-700">${getFieldValue('Profile Title')}</a>
                        </div>
                    </div>
                </section>
            `;

        case 'Navigation Header':

            return `
            <section class="block group relative" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                <button class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-3 py-1 rounded hover:bg-opacity-70 transition hidden group-hover:block edit-btn"
                    data-target="modalEditBlock" data-id="${blockData.id}" onclick="openEditModal(this)">Edit</button>
                <div class="container">
                    <div x-data="{ open: false }"
                    class="mx-auto flex max-w-screen-xl flex-col p-5 md:flex-row md:items-center md:justify-between md:px-6 lg:px-8">
                    <div class="flex flex-row items-center justify-between lg:justify-start">
                        <a href="#"
                        class="text-lg font-bold tracking-tighter tracking-wide text-blue-600 transition duration-500 ease-in-out lg:pr-8">
                        wickedblocks
                        </a>
                        <button class="rounded-lg focus:outline-none md:hidden" @click="open = !open">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="size-8">
                            <path x-show="!open" fill-rule="evenodd"
                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                            <path x-show="open" fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" style="display: none"></path>
                        </svg>
                        </button>
                    </div>

                    <nav :class="{'flex': open, 'hidden': !open}"
                        class="hidden grow flex-col items-center border-blue-600 pb-4 md:flex md:flex-row md:justify-end md:pb-0 lg:border-l-2 lg:pl-2">
                        <a class="mt-2 px-4 py-2 text-sm text-gray-500 hover:text-blue-600 focus:outline-none md:mt-0"
                        href="#">About</a>
                        <a class="mt-2 px-4 py-2 text-sm text-gray-500 hover:text-blue-600 focus:outline-none md:mt-0"
                        href="#">Contact</a>
                        <div @click.away="open = false" class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="mt-2 flex w-full flex-row items-center px-4 py-2 text-left text-sm text-gray-500 hover:text-blue-600 focus:outline-none md:mt-0 md:inline md:w-auto">
                            <span>Dropdown List</span>
                            <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
                            class="ml-1 mt-1 inline size-4 rotate-0 transition-transform duration-200 md:-mt-1">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-30 mt-2 w-full origin-top-right rounded-md shadow-lg md:w-48"
                            style="display: none">
                            <div class="rounded-md bg-white p-2 shadow">
                            <a class="mt-2 block px-4 py-2 text-sm text-gray-500 hover:text-blue-600 focus:outline-none md:mt-0"
                                href="#">Link #1</a>
                            <a class="mt-2 block px-4 py-2 text-sm text-gray-500 hover:text-blue-600 focus:outline-none md:mt-0"
                                href="#">Link #2</a>
                            <a class="mt-2 block px-4 py-2 text-sm text-gray-500 hover:text-blue-600 focus:outline-none md:mt-0"
                                href="#">Link #3</a>
                            </div>
                        </div>
                        </div>
                    </nav>
                    </div>
                </div>
            </section>
            `;
        default:
            return `<div class="p-4 bg-red-100 rounded">Unknown block type</div>`;
    }
}

function escapeHtml(str) {
    str = String(str || '<undefined>'); // Convert null/undefined to empty string

    return str.replace(/[&<>"'`]/g, function (match) {
        const escapeMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#x27;',
            '`': '&#x60;'
        };
        return escapeMap[match];
    });
}

function extractGroupedItems(blockData, groupName) {
    const group = blockData.block_field_groups?.find(
        g => g.group_name === groupName
    );

    if (!group) return [];

    const grouped = {};

    group.block_field_group_items.forEach(item => {
        const pos = item.position;
        if (!grouped[pos]) grouped[pos] = {};
        grouped[pos][item.field_name] = item.field_value;
    });

    return Object.values(grouped);
}