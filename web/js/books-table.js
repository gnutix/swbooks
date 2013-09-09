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

            // Loop over the current row's cells
            $('td', row).each(function (cellIndex) {

                // If the cell has already been flagged, we remove it and go on with the next iteration
                if ($(this).data('remove')) {
                    $(this).remove();
                    return true;
                }

                var cell = $(this),
                    cellHTML = cell.outerHTML();

                // Loop over the era's next books rows
                row.nextAll().each(function () {

                    // PS: do not try to mix this children() with the above nextAll() or you will blow up performances
                    var nextCell = $(this).children('td:eq(' + cellIndex + ')');

                    // If there's no next cell or the next cell's HTML is not the same as its parent, we stop the loop
                    if (0 === nextCell.length || nextCell.outerHTML() !== cellHTML) {
                        return false;
                    }

                    // We flag it to be removed
                    nextCell.data('remove', true);

                    // We change the current cell's rowspan
                    cell.attr(
                        'rowspan',
                        parseInt(undefined !== cell.attr('rowspan') ? cell.attr('rowspan') : 1) + 1
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
