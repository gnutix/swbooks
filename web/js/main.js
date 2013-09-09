$(document).ready(function () {
    var booksTable = $('table.books-list');

    // Full colspan
    $('thead.era-header th', booksTable).each(function () {
        $(this).attr('colspan', $('thead tr th', $(this).parents('table')).length).removeAttr('data-full-colspan');
    });

    // Loop over the books rows
    $.each($('tbody tr', booksTable).get().reverse(), function () {
        var row = $(this);

        // Then over the columns
        $('td', row).each(function (currentColumnIndex) {
            var currentColumn = $(this),
                currentColumnHTML = currentColumn.outerHTML(),
                nextRows = row.prevAll('tr'),
                nbNextRows = nextRows.length,
                column = null,
                lastColumn = null;

            // Loop over the next rows
            nextRows.each(function (columnIndex) {
                if (null !== column) {
                    lastColumn = column;
                }
                column = $(this).find('td').eq(currentColumnIndex);

                // If the next column is different, there's nothing to do
                if (column.outerHTML() !== currentColumnHTML) {
                    if (currentColumn.data('remove')) {
                        column.attr('rowspan', rowspan + 1);
                    }

                    return false;
                }

                currentColumn.data('remove');
                column.remove();

                return true;
            });
        });
    });

    /* Once the tagging is done, here comes the real work
    $('tbody tr td', booksTable).each(function () {
        if ($(this).data('remove')) {
            $(this).remove();
        } else if (1 < $(this).data('rowspan')) {
            $(this).attr('rowspan', $(this).data('rowspan'));
        }
    });
    */
});
