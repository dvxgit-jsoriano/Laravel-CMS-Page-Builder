function loadBlocksPerTemplate(templateName) {
    $("#draggable-list").empty();

    // Load blocks depending on templates.
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
            break;

        case "Barista Cafe":
            $("#draggable-list").append(`
                <li data-block-type="Navigation">
                    <h4>Navigation</h4>
                    <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg">
                </li>
            `);
            $("#draggable-list").append(`
                <li data-block-type="Hero">
                    <h4>Hero</h4>
                    <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg">
                </li>
            `);
            $("#draggable-list").append(`
                <li data-block-type="About">
                    <h4>About</h4>
                    <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg">
                </li>
            `);
            $("#draggable-list").append(`
                <li data-block-type="Baristas">
                    <h4>Baristas</h4>
                    <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg">
                </li>
            `);
            $("#draggable-list").append(`
                <li data-block-type="Menu">
                    <h4>Menu</h4>
                    <img src="https://flowbite.com/docs/images/carousel/carousel-1.svg">
                </li>
            `);

            break;

        default:
    }
}


function getBlockTemplateFromServer(templateName, blockData) {

    // This is an arrow function used as a helper to find the field key and return the field value.
    const getFieldValue = (key) => {
        const field = blockData.block_fields.find(f => f.field_key === key);
        return field ? escapeHtml(field.field_value) : '';
    };

    // This is an arrow function used as a helper to find the field key and return the field html.
    const getFieldHTML = (key) => {
        const field = blockData.block_fields.find(f => f.field_key === key);
        return field ? field.field_value : '';
    };

    // This is an arrow function used as helper to attach action buttons, Edit and Delete.
    const renderActionButtons = (blockId) => `
        <button class="pb-render-button edit-btn" style="left: 0.5rem;"
            data-target="modalEditBlock" data-id="${blockId}" onclick="openEditModal(this)">Edit</button>
        <button class="pb-render-button delete-btn" style="left: 3.75rem;"
            data-target="modalDeleteBlock" data-id="${blockId}" onclick="openDeleteModal(this)">Delete</button>
    `;

    // get blocks depending on templates...
    switch (templateName) {
        case "Default Template":
            // get specific block type
            switch (blockData.type) {
                case 'navigation':
                    const centerItems = extractGroupedItems(blockData, 'Center Links');

                    const centerLinks = centerItems.map(item => `
                        <a href="${item.URL}" class="p-4 underline-none text-gray-800 hover:text-gray-700">${item.Title}</a>
                    `).join('');

                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
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
                case 'hero':
                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            ${renderActionButtons(blockData.id)}
                            <div class="p-6 bg-blue-100 rounded shadow">
                                <h1 class="text-2xl font-bold mb-2">${getFieldValue('Title')}</h1>
                                <p class="text-gray-700">${getFieldValue('Description')}</p>
                            </div>
                        </section>
                    `;
                default:
                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            ${renderActionButtons(blockData.id)}
                            <div class="p-4 bg-red-100 rounded">Unknown block type</div>
                        </section>
                    `;
            }
        case "Wicked Blocks":
            // get specific block type
            switch (blockData.type) {
                case 'Navigation Header':
                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            ${renderActionButtons(blockData.id)}
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
                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            ${renderActionButtons(blockData.id)}
                            <div class="p-4 bg-red-100 rounded">Unknown block type</div>
                        </section>
                    `;
            }
        case "Barista Cafe":
            // get specific block type
            switch (blockData.type) {
                case 'Navigation':
                    const menuItems = extractGroupedItems(blockData, 'Menu Links');

                    const menuLinks = menuItems.map(item => `
                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="${item.URL}">${item.Title}</a>
                        </li>
                    `).join('');

                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            ${renderActionButtons(blockData.id)}
                            <nav class="navbar navbar-expand-lg">
                                <div class="container">
                                    <a class="navbar-brand d-flex align-items-center" href="#">
                                        <img src="${getFieldValue('Logo Image URL')}" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
                                        ${getFieldValue('Logo Title')}
                                    </a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarNav">
                                        <ul class="navbar-nav ms-lg-auto">
                                            ${menuLinks}
                                        </ul>
                                        <div class="ms-lg-3">
                                            <a class="btn custom-btn custom-border-btn" href="${getFieldValue('Reservation URL')}">
                                                ${getFieldValue('Reservation Title')}
                                                <i class="bi-arrow-up-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </section>
                    `;
                case 'Hero':
                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            <div class="hero-section d-flex justify-content-center align-items-center" id="section_1">
                            ${renderActionButtons(blockData.id)}
                                <div class="container">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 col-12 mx-auto">
                                            <em class="small-text">${getFieldValue('Welcome Message')}</em>
                                            <h1>${getFieldValue('Title')}</h1>
                                            <p class="text-white mb-4 pb-lg-2">
                                                ${getFieldValue('Sub Title')}
                                            </p>
                                            <a class="btn custom-btn custom-border-btn smoothscroll me-3" href="${getFieldValue('Left Button Link Url')}">
                                            ${getFieldValue('Left Button Text')}
                                            </a>
                                            <a class="btn custom-btn smoothscroll me-2 mb-2" href="${getFieldValue('Right Button Link Url')}"><strong>${getFieldValue('Right Button Text')}</strong></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="hero-slides">
                                </div>
                        </section>
                    `;
                case "About":
                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            <div class="about-section section-padding" id="section_2">
                                ${renderActionButtons(blockData.id)}
                                <div class="section-overlay"></div>
                                <div class="container">
                                    <div class="row align-items-center">

                                        <div class="col-lg-6 col-12">
                                            <div class="ratio ratio-1x1">
                                                <video autoplay="" loop="" muted="" class="custom-video" poster="">
                                                    <source src="${getFieldValue('Video Frame URL')}" type="video/mp4">

                                                    Your browser does not support the video tag.
                                                </video>

                                                <div class="about-video-info d-flex flex-column">
                                                    <h4 class="mt-auto">${getFieldValue('Video Frame Description')}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-5 col-12 mt-4 mt-lg-0 mx-auto">
                                            <em class="text-white">${getFieldValue('Website')}</em>

                                            <h2 class="text-white mb-3">${getFieldValue('Title')}</h2>

                                            <p>${getFieldHTML('Description')}</p>

                                            <a href="#barista-team" class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4">Meet Baristas</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section>
                    `;
                case "Baristas":
                    const baristas = extractGroupedItems(blockData, 'Barista Cards');

                    const baristaCards = baristas.map(item => {
                        return `
                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <div class="team-block-wrap">
                                    <div class="team-block-info d-flex flex-column">
                                        <div class="d-flex mt-auto mb-3">
                                            <h4 class="text-white mb-0">${item['Name']}</h4>

                                            <p class="badge ms-4"><em>${item['Position']}</em></p>
                                        </div>

                                        <p class="text-white mb-0">${item['Description']}</p>
                                    </div>

                                    <div class="team-block-image-wrap">
                                        <img src="${item['Person Picture URL']}" class="team-block-image img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        `}).join('');


                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            <div class="barista-section section-padding section-bg" id="barista-team">
                                ${renderActionButtons(blockData.id)}
                                <div class="container">
                                    <div class="row justify-content-center">

                                        <div class="col-lg-12 col-12 text-center mb-4 pb-lg-2">
                                            <em class="text-white">${getFieldValue('Top Header')}</em>

                                            <h2 class="text-white">${getFieldValue('Header Title')}</h2>
                                        </div>

                                        ${baristaCards}

                                    </div>
                                </div>
                            </div>
                        </section>
                    `;
                case "Menu":

                    const leftMenu = extractGroupedItems(blockData, 'Barista Cards');

                    const leftMenuCards = leftMenu.map(item => {
                        return `
                            <div class="menu-block">
                                <div class="d-flex">
                                    <h6>${item['Menu Name']}</h6>

                                    <span class="underline"></span>

                                    <strong class="ms-auto">$12.50</strong>
                                </div>

                                <div class="border-top mt-2 pt-2">
                                    <small>Fresh brewed coffee and steamed milk</small>
                                </div>
                            </div>
                        `}).join('');


                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            <div class="menu-section section-padding" id="section_3">
                                ${renderActionButtons(blockData.id)}
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-4 mb-lg-0">

                                            <div class="menu-block-wrap">
                                                <div class="text-center mb-4 pb-lg-2">
                                                    <em class="text-white">${getFieldValue('Left Top Header')}</em>
                                                    <h4 class="text-white">${getFieldValue('Left Header Title')}</h4>
                                                </div>

                                                ${leftMenuCards}

                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="menu-block-wrap">
                                                <div class="text-center mb-4 pb-lg-2">
                                                    <em class="text-white">Favourite Menu</em>
                                                    <h4 class="text-white">Coffee</h4>
                                                </div>

                                                <div class="menu-block">
                                                    <div class="d-flex">
                                                        <h6>Latte</h6>

                                                        <span class="underline"></span>

                                                        <strong class="text-white ms-auto"><del>$12.50</del></strong>

                                                        <strong class="ms-2">$7.50</strong>
                                                    </div>

                                                    <div class="border-top mt-2 pt-2">
                                                        <small>Fresh brewed coffee and steamed milk</small>
                                                    </div>
                                                </div>

                                                <div class="menu-block my-4">
                                                    <div class="d-flex">
                                                        <h6>
                                                            White Coffee
                                                            <span class="badge ms-3">Recommend</span>
                                                        </h6>

                                                        <span class="underline"></span>

                                                        <strong class="ms-auto">$5.90</strong>
                                                    </div>

                                                    <div class="border-top mt-2 pt-2">
                                                        <small>Brewed coffee and steamed milk</small>
                                                    </div>
                                                </div>

                                                <div class="menu-block">
                                                    <div class="d-flex">
                                                        <h6>Chocolate Milk</h6>

                                                        <span class="underline"></span>

                                                        <strong class="ms-auto">$5.50</strong>
                                                    </div>

                                                    <div class="border-top mt-2 pt-2">
                                                        <small>Rich Milk and Foam</small>
                                                    </div>
                                                </div>

                                                <div class="menu-block my-4">
                                                    <div class="d-flex">
                                                        <h6>Greentea</h6>

                                                        <span class="underline"></span>

                                                        <strong class="ms-auto">$7.50</strong>
                                                    </div>

                                                    <div class="border-top mt-2 pt-2">
                                                        <small>Fresh brewed coffee and steamed milk</small>
                                                    </div>
                                                </div>

                                                <div class="menu-block">
                                                    <div class="d-flex">
                                                        <h6>Dark Chocolate</h6>

                                                        <span class="underline"></span>

                                                        <strong class="ms-auto">$7.25</strong>
                                                    </div>

                                                    <div class="border-top mt-2 pt-2">
                                                        <small>Rich Milk and Foam</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    `;
                default:
                    return `
                        <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                            ${renderActionButtons(blockData.id)}
                            <div class="p-4 bg-red-100 rounded">Unknown block type</div>
                        </section>
                    `;
            }
        default:
            return `
                <section class="section-group" data-block-id="${blockData.id}" data-block-type="${blockData.type}">
                    ${renderActionButtons(blockData.id)}
                    <div class="p-4 bg-red-100 rounded">Unknown block type</div>
                </section>
            `;
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