$(document).ready(function () {

    // Fake drag source - clone instead of move
    new Sortable(document.getElementById('draggable-list'), {
        group: {
            name: 'blocks',
            pull: 'clone', // copy instead of move
            put: false     // don't allow dropping here
        },
        sort: false
    });

    // Drop target
    new Sortable(document.getElementById('sortable-list'), {
        group: 'blocks',
        animation: 150,
        sort: true,
        onAdd: function (evt) {
            // Get block type BEFORE removing the item
            const type = evt.item.dataset.blockType;

            // Now safely remove the item
            evt.item.remove();

            // Show loading (optional)
            const loading = document.createElement('div');
            loading.textContent = "Loading...";
            loading.className = "text-center p-4 text-gray-600";
            evt.to.appendChild(loading);

            const response = {
                type: 'hero',
                title: 'This is a title',
                description: 'This is a description'
            };

            const blockData = response;
            console.log(blockData);

            const blockHTML = getBlockTemplateFromServer(blockData);

            const wrapper = document.createElement('div');
            wrapper.innerHTML = blockHTML;

            // Remove loading before adding the block
            loading.remove();

            evt.to.appendChild(wrapper.firstElementChild);

            // AJAX to server to create block
            /* $.ajax({
                url: 'create-block.php',
                method: 'POST',
                data: { type: type },
                success: function (response) {
                    console.log("Server response:", response);

                    try {
                        const blockData = response;
                        console.log(blockData);

                        const blockHTML = getBlockTemplateFromServer(blockData);

                        const wrapper = document.createElement('div');
                        wrapper.innerHTML = blockHTML;

                        // Remove loading before adding the block
                        loading.remove();

                        evt.to.appendChild(wrapper.firstElementChild);
                    } catch (err) {
                        console.error("Error parsing server response:", err);
                        loading.remove(); // Important in case of error too
                        alert("Failed to create block.");
                    }
                },
                error: function () {
                    loading.remove();
                    alert("Server error while creating block.");
                }
            }); */

            // Remove loading before adding the block
            loading.remove();
        }
    });

    function getBlockTemplateFromServer(blockData) {
        // Check the type of block and return HTML accordingly
        switch (blockData.type) {
            case 'hero':
                return `
                    <section data-id="${blockData.id}" class="group relative">
                        <button class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-3 py-1 rounded hover:bg-opacity-70 transition hidden group-hover:block edit-btn">
                            Edit
                        </button>
                        <div class="p-6 bg-blue-100 rounded shadow">
                            <h1 class="text-2xl font-bold mb-2">${escapeHtml(blockData.title)}</h1>
                            <p class="text-gray-700">${escapeHtml(blockData.description)}</p>
                        </div>
                    </section>
                `;
            case 'banner':
                return `
                    <section data-id="${blockData.id}" class="group relative">
                        <button class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-3 py-1 rounded hover:bg-opacity-70 transition hidden group-hover:block edit-btn">
                            Edit
                        </button>
                        <div class="p-4 bg-yellow-100 rounded shadow text-center">
                            <p class="font-semibold">${escapeHtml(blockData.description)}</p>
                        </div>
                    </section>
                `;
            case 'navigation':
                const logoSrc = blockData.logo?.src || '';
                const logoLabel = blockData.logo?.label || '';
                const centerLinks = blockData.centerLinks?.map(link => `
                    <a href="${link.url}" class="p-4 underline-none text-gray-800 hover:text-gray-700">${link.title}</a>
                `).join('') || '';

                const profileTitle = blockData.profileLink?.title || '';
                const profileUrl = blockData.profileLink?.url || '#';

                return `
                    <section data-id="${blockData.id}" class="group relative">
                        <button class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-3 py-1 rounded hover:bg-opacity-70 transition hidden group-hover:block edit-btn">
                            Edit
                        </button>
                        <div class="p-4 flex justify-between items-center bg-green-100 rounded shadow">
                            <div class="flex items-center">
                                <img src="${logoSrc}" class="h-8" alt="Logo">
                                <span class="ms-2 font-medium">${logoLabel}</span>
                            </div>
                            <div class="flex flex-row">
                                ${centerLinks}
                            </div>
                            <div>
                                <a href="${profileUrl}" class="p-4 underline-none text-gray-800 hover:text-gray-700">${profileTitle}</a>
                            </div>
                        </div>
                    </section>
                `;
            default:
                return `<div class="p-4 bg-red-100 rounded">Unknown block type</div>`;
        }
    }

    function escapeHtml(str) {
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
});