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
            // Prevent the drop if there is no selected page.
            if (!globalPageId) {
                alert("Please select a page");

                // Undo the drop by removing the item
                evt.item.remove();
                return;
            }

            // Show loading overlay at the start of event.
            $('#loading-overlay').addClass('show');

            // Get block type BEFORE removing the item
            const type = evt.item.dataset.blockType;

            // Now safely remove the item
            evt.item.remove();

            let response = createBlock(type);

            const blockData = response;
            console.log(blockData);
            console.log(blockData.type);

            const blockHTML = getBlockTemplateFromServer(blockData);

            const wrapper = document.createElement('div');
            wrapper.innerHTML = blockHTML;

            evt.to.appendChild(wrapper.firstElementChild);

            // Remove loading at the end of this event.
            $('#loading-overlay').removeClass('show');
        },

        onEnd: function (evt) {
            const updatedPositions = [];

            $('#sortable-list .block').each(function (index) {
                const blockId = $(this).data('block-id'); // jQuery .data() reads data-block-id
                if (blockId) {
                    updatedPositions.push({
                        id: blockId,
                        position: index + 1
                    });
                }
            });

            // Send AJAX to update positions in DB
            $.ajax({
                url: '/update-block-positions',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                data: JSON.stringify({ positions: updatedPositions }),
                success: function (data) {
                    console.log('Positions updated', data);
                },
                error: function (xhr, status, error) {
                    console.error('Failed to update positions', error);
                }
            });
        }
    });
});

/**
 * Page Builder page functions
 */
function openTab() {
    // Clone the layout content using jQuery
    const $clonedLayout = $('#sortable-list').clone();

    // Remove all elements with class 'edit-btn' and 'delete-btn'
    $clonedLayout.find('.edit-btn').remove();
    $clonedLayout.find('.delete-btn').remove();
    $clonedLayout.find('.loading-overlay').remove();

    const layoutContent = $clonedLayout.html();

    const html =
        '<!DOCTYPE html>' +
        '<html lang="en">' +
        '<head>' +
        '<meta charset="UTF-8">' +
        '<title>Exported Layout</title>' +
        '<script src="https://cdn.tailwindcss.com"><\/script>' +
        '</head>' +
        '<body class="bg-gray-50">' +
        layoutContent +
        '</body>' +
        '</html>';

    const newWindow = window.open('', '_blank');
    $(newWindow.document).ready(function () {
        newWindow.document.write(html);
        newWindow.document.close();
    });
}


function openModal(triggerEl) {
    const target = $(triggerEl).data('target');
    $('#' + target).show(); // or .fadeIn() if you want animation
}

function closeModal(triggerEl) {
    const target = $(triggerEl).data('target');
    $('#' + target).hide(); // or .fadeOut()
}