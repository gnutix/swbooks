wrapper('booksTableManager', ['ObjectSwapper'], function() {
    'use strict';

    /**
     * Manager for the books HTML table
     */
    function BooksTableManager(inputBooksTable, inputLanguageFilterForm) {
        this.booksTable = inputBooksTable;
        this.languageFilterCheckboxes = $('input[type="checkbox"]', inputLanguageFilterForm);
        this.originalBooksTable = this.booksTable.clone();
        this.hasBeenInitialized = false;

        /**
         * Register the click event for the language filter links
         */
        this.registerLanguageFilterFormEvent = function () {
            var that = this;

            this.languageFilterCheckboxes.on('change', function () {
                that.init(true);
            });
        };

        /**
         * Get the list of shown languages
         */
        this.getShownLanguages = function () {
            var displayLanguages = [];

            this.languageFilterCheckboxes.each(function () {
                if ($(this).is(':checked')) {
                    displayLanguages.push($(this).attr('value'));
                }
            });

            return displayLanguages;
        };

        /**
         * Automatically set the colspan attribute of the "eras" headings to the table's number of columns
         */
        this.scaleEraHeaders = function () {
            $('thead.era-header th', this.booksTable).attr(
                'colspan',
                $('thead.main-header tr th', this.booksTable).length
            );
        };

        /**
         * Filter the table's rows by publication language
         */
        this.filterByLanguages = function (languages) {

            // Show everything
            $('tbody tr', this.booksTable).removeClass('hidden');

            // If we've specified languages as a filter
            if (undefined !== languages) {
                var languageSelector = [];

                $.each(languages, function (index, language) {
                    languageSelector.push('[lang=' + language + ']');
                });

                // We hide all the rows that are not in the given languages
                $('tbody tr:not(' + languageSelector.join(',') + ')', this.booksTable).addClass('hidden');
            }
        };

        /**
         * Automatically applies rowspan on columns that have multiple cells with the same contents
         */
        this.applyAutoRowspan = function () {

            // Loop over all the books rows
            $('tbody tr:not(.hidden)', this.booksTable).each(function () {
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
                    row.nextAll('tr:not(.hidden)').each(function () {

                        // Don't try to mix this children() with the above nextAll() or you will blow up performances
                        var nextCell = $(this).children('td:eq(' + cellIndex + ')');

                        // If the cell and next cells are not identical, we stop the loop
                        if (0 === nextCell.length || nextCell.outerHTML() !== cellHTML) {
                            return false;
                        }

                        // We flag it to be removed
                        nextCell.data('remove', true);

                        // We change the current cell's rowspan
                        cell.attr(
                            'rowspan',
                            parseInt((undefined !== cell.attr('rowspan') ? cell.attr('rowspan') : 1), 10) + 1
                        );

                        return true;
                    });

                    return true;
                });
            });
        };
    }

    // Public methods
    BooksTableManager.prototype = {

        /**
         * Initialization of the object
         */
        init: function (reset) {
            if (false === this.hasBeenInitialized) {
                this.hasBeenInitialized = true;

                // Register the click event for the language filter links
                this.registerLanguageFilterFormEvent();
            }

            // Reset the table to its original state (without removed contents, rowspans, etc)
            if (undefined !== reset) {
                this.booksTable = ObjectSwapper.replaceWith(this.booksTable, this.originalBooksTable);
            }

            // Prepare the table
            this.scaleEraHeaders();
            this.filterByLanguages(this.getShownLanguages());
            this.applyAutoRowspan();
        }
    };

    return BooksTableManager;
});
