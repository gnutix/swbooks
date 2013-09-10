$(document).ready(function () {
    'use strict';

    (new BooksTableManager($('table.books-list'), $('div.languages-container'))).init();
});
