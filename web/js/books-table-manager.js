wrapper('booksTableManager', ['ObjectSwapper'], function() {
    'use strict';

    /**
     * Manager for the books HTML table
     */
    function BooksTableManager(inputBooksTable, inputLanguageSwitcher) {
        this.booksTable = inputBooksTable;
        this.languageSwitcher = inputLanguageSwitcher;
        this.originalBooksTable = this.booksTable.clone();
        this.hasBeenInitialized = false;

        /**
         * Get the list of publication languages available
         */
        this.getAllPublicationLanguages = function () {
            var languages = [];
            $('tbody tr', this.booksTable).each(function () {
                languages.push($(this).attr('lang'));
            });

            return $.unique(languages);
        };

        /**
         * Create the language filter switchers links
         */
        this.createLanguageSwitchers = function () {
            var languageList = $('<ul></ul>'),
                filterElement = function (text, language) {
                    return $('<li></li>').html(
                        $('<a></a>').attr({
                            'href': '#',
                            'data-toggle': 'books-table-language-switcher',
                            'data-language': undefined !== language ? language : 'all'
                        }).html(text)
                    );
                };

            // First, create a link to reset the filters
            languageList.prepend(filterElement('Reset the filter', undefined));

            // Then loop over the publication languages available to create each language filter link
            $.each(this.getAllPublicationLanguages(), function (index, language) {
                languageList.append(filterElement(language, language));
            });

            // Add the list to the HTML container
            this.languageSwitcher.html(languageList);

            // Register the click event for the filter links
            this.registerLanguageSwitcherEvent();
        };

        /**
         * Register the click event for the language filter links
         */
        this.registerLanguageSwitcherEvent = function () {
            var that = this;

            $('[data-toggle="books-table-language-switcher"]').on('click', function (event) {
                event.preventDefault();

                that.init($(this).attr('data-language'), true);
            });
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
        this.filterByLanguage = function (language) {

            // Show everything
            $('tbody tr', this.booksTable).removeClass('hidden');

            // If we've specified a language as a filter
            if (undefined !== language && 'all' !== language) {

                // We hide all the rows that are not in the given language
                $('tbody tr:not([lang=' + language + '])', this.booksTable).addClass('hidden');
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
        init: function (language, reset) {
            if (false === this.hasBeenInitialized) {
                this.hasBeenInitialized = true;

                // Create the language switcher only at the object's initialization
                this.createLanguageSwitchers();
            }

            // Reset the table to its original state (without removed contents, rowspans, etc)
            if (undefined !== reset) {
                this.booksTable = ObjectSwapper.replaceWith(this.booksTable, this.originalBooksTable);
            }

            // Prepare the table
            this.scaleEraHeaders();
            this.filterByLanguage(language);
            this.applyAutoRowspan();
        }
    };

    return BooksTableManager;
});
