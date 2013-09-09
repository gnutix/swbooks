$(document).ready(function () {
    var booksTable = $('table.books-list');

    // Full colspan
    $('thead.era-header th', booksTable).each(function () {
        $(this).attr('colspan', $('thead tr th', $(this).parents('table')).length).removeAttr('data-full-colspan');
    });

    // Loop over the books rows
    $('tbody tr', booksTable).each(function () {
        var row = $(this);

        // Then over the columns
        $('td', row).each(function (columnIndex) {
            var column = $(this),
                columnHTML = column.outerHTML();

            // Set the current rowspan (from the DOM) on the object, or 1 otherwise
            column.data('rowspan', undefined !== column.attr('rowspan') ? parseInt(column.attr('rowspan')) : 1);

            // Loop over the next rows
            row.nextAll('tr').each(function () {
                var nextColumn = $(this).find('td').eq(columnIndex);

                // If the next column is different, there's nothing to do
                if (nextColumn.outerHTML() !== columnHTML) {
                    return false;
                }

                nextColumn.data('remove', true);
                column.data('rowspan', column.data('rowspan') + 1);

                return true;
            });
        });
    });

    // Once the tagging is done, here comes the real work
    $('tbody tr td', booksTable).each(function () {
        if ($(this).data('remove')) {
            $(this).remove();
        } else if (1 < $(this).data('rowspan')) {
            $(this).attr('rowspan', $(this).data('rowspan'));
        }
    });
});
