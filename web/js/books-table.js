var BooksTable = function (inputTable) {
    'use strict';

    var booksTable = inputTable,
        scaleEraHeaders,
        applyAutoRowspan;

    scaleEraHeaders = function () {
        $('thead.era-header th', booksTable).attr('colspan', $('thead.main-header tr th', booksTable).length);
    };

    applyAutoRowspan = function () {

        // Loop over all the books rows
        $('tbody tr', booksTable).each(function () {
            var row = $(this);

            // Then over the columns
            $('td', row).each(function (columnIndex) {

                // If the column has already been flagged, we remove it and go on with the next iteration
                if ($(this).data('remove')) {
                    $(this).remove();
                    return true;
                }

                var column = $(this),
                    columnHTML = column.outerHTML();

                // Loop over the next rows
                row.nextAll().each(function () {

                    // PS: do not try to mix this children() with the above nextAll() or you will blow up performances
                    var nextColumn = $(this).children('td:eq(' + columnIndex + ')');

                    // If the columns are the same
                    if (nextColumn.outerHTML() !== columnHTML) {
                        return false;
                    }

                    // We flag it to be removed
                    nextColumn.data('remove', true);

                    // We change the current column rowspan
                    column.attr(
                        'rowspan',
                        parseInt(undefined !== column.attr('rowspan') ? column.attr('rowspan') : 1) + 1
                    );
                });
            });
        });
    };

    return {
        scaleEraHeaders: scaleEraHeaders,
        applyAutoRowspan: applyAutoRowspan
    };
};
