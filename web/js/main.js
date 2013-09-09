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
                columnHTML = column.html(),
                rowspan = undefined !== column.attr('rowspan') ? parseInt(column.attr('rowspan')) : 1;

            // Loop over the next rows
            row.nextAll('tr').each(function () {
                var nextColumn = $(this).find('td').eq(columnIndex);

                // If the next column is different, there's nothing to do
                if (nextColumn.html() !== columnHTML) {
                    return false;
                }

                rowspan += 1;

                nextColumn.attr('data-remove', true);
                column.attr('rowspan', rowspan);

                return true;
            });
        });
    });

    $('tbody tr td[data-remove]', booksTable).remove();
});
