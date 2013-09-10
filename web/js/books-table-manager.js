/**
 * Manager for the books HTML table
 */
var BooksTableManager = function (inputBooksTable, inputLanguageSwitcher) {
    'use strict';

    var booksTable = inputBooksTable,
        languageSwitcher = inputLanguageSwitcher,
        originalBooksTable = booksTable.clone(),
        hasBeenInitialized = false,
        init,
        resetObjectToOriginalState,
        getAllPublicationLanguages,
        createLanguageSwitchers,
        registerLanguageSwitcherEvent,
        scaleEraHeaders,
        filterByLanguage,
        applyAutoRowspan;

    /**
     * Initialization of the object
     */
    init = function (language, reset) {
        if (false === hasBeenInitialized) {
            hasBeenInitialized = true;

            // Create the language switcher only at the object's initialization
            createLanguageSwitchers();
        }

        // Reset the table to its original state (without removed contents, rowspans, etc)
        if (undefined !== reset) {
            booksTable = resetObjectToOriginalState(booksTable, originalBooksTable);
        }

        // Prepare the table
        scaleEraHeaders();
        filterByLanguage(language);
        applyAutoRowspan();
    };

    /**
     * Allows to entirely replace an object with another one
     */
    resetObjectToOriginalState = function (object, original) {
        var originalState = original.clone();

        object.replaceWith(originalState);
        object = originalState;

        return object;
    };

    /**
     * Get the list of publication languages available
     */
    getAllPublicationLanguages = function () {
        var languages = [];
        $('tbody tr', booksTable).each(function () {
            languages.push($(this).attr('lang'));
        });

        return $.unique(languages);
    };

    /**
     * Create the language filter switchers links
     */
    createLanguageSwitchers = function () {
        var languageList = $('<ul></ul>'),
            filterElement = function (text, language) {
                return $('<li></li>').html(
                    $('<a></a>').attr({
                        'href': '#',
                        'data-toggle': 'books-table-language-switcher',
                        'data-language': undefined !== language ? language : 'all'
                    }).html(text)
                )
            };

        // First, create a link to reset the filters
        languageList.prepend(filterElement('Reset the filter', undefined));

        // Then loop over the publication languages available to create each language filter link
        $.each(getAllPublicationLanguages(booksTable), function (index, language) {
            languageList.append(filterElement(language, language));
        });

        // Add the list to the HTML container
        languageSwitcher.html(languageList);

        // Register the click event for the filter links
        registerLanguageSwitcherEvent();
    };

    /**
     * Register the click event for the language filter links
     */
    registerLanguageSwitcherEvent = function () {
        $('[data-toggle="books-table-language-switcher"]').on('click', function (event) {
            event.preventDefault();

            init($(this).attr('data-language'), true);
        });
    };

    /**
     * Automatically set the colspan attribute of the "eras" headings to the table's number of columns
     */
    scaleEraHeaders = function () {
        $('thead.era-header th', booksTable).attr('colspan', $('thead.main-header tr th', booksTable).length);
    };

    /**
     * Filter the table's rows by publication language
     */
    filterByLanguage = function (language) {

        // Show everything
        $('tbody tr', booksTable).removeClass('hidden');

        // If we've specified a language as a filter
        if (undefined !== language && 'all' !== language) {

            // We hide all the rows that are not in the given language
            $('tbody tr:not([lang=' + language + '])', booksTable).addClass('hidden');
        }
    };

    /**
     * Automatically applies rowspan on columns that have multiple cells with the same contents
     */
    applyAutoRowspan = function () {

        // Loop over all the books rows
        $('tbody tr:not(.hidden)', booksTable).each(function () {
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

                    return true;
                });

                return true;
            });
        });
    };

    return {
        init: init
    };
};
