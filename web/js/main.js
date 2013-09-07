$(document).ready(function () {

    // Full colspan
    $('[data-full-colspan]').each(function () {
        $(this).attr('colspan', $('thead tr th', $(this).parents('table')).length).removeAttr('data-full-colspan');
    });

    // Useful variables
    var tableContent = $('table.books-list tbody'),
        booksRowsSelector = 'tr:not([data-era-heading])';

    // Loop over the eras headings
    $('tr[data-era-heading]', tableContent).each(function () {

        // Loop over the next rows that are not era headings
        $(this).nextAll(booksRowsSelector).each(function () {
            var row = $(this);

            // Then over the columns
            $('td', row).each(function (columnIndex) {
                var column = $(this);

                // Set the current rowspan (from the DOM) on the object, or 1 otherwise
                column.data('rowspan', undefined !== column.attr('rowspan') ? parseInt(column.attr('rowspan')) : 1);

                // Loop over the next rows
                row.nextAll(booksRowsSelector).each(function () {
                    var nextColumn = $(this).find('td').eq(columnIndex);

                    // If the next column is different, there's nothing to do
                    if (nextColumn.outerHTML() !== column.outerHTML()) {
                        return false;
                    }

                    nextColumn.data('remove', 1);
                    column.data('rowspan', column.data('rowspan') + 1);

                    return true;
                });
            });
        });
    });

    // Once the tagging is done, here comes the real work
    $('td', tableContent).each(function () {
        if (1 == $(this).data('remove')) {
            $(this).remove();
        } else if (1 < $(this).data('rowspan')) {
            $(this).attr('rowspan', $(this).data('rowspan'));
        }
    });
});
