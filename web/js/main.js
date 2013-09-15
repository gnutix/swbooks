$(document).ready(function () {
    'use strict';

    // Fix glyphicon position in accordion (http://jsfiddle.net/zessx/R6EAW/12/)
    function toggleChevron(e) {
        $(e.target)
            .prev('.panel-heading')
            .find('i.indicator')
            .toggleClass('glyphicon-chevron-right glyphicon-chevron-down');
    }
    $('div[data-accordion]').on('hidden.bs.collapse', toggleChevron).on('shown.bs.collapse', toggleChevron);

    // Hide elements with class "js-hidden"
    $('.js-hidden').hide();

    // Add the tooltip to every abbreviations
    $('abbr[title]').tooltip({placement: 'bottom'});

    // Init the books table manager
    (new BooksTableManager($('table[data-books-list]'), $('form[data-filter-languages-form]'))).init();
});
