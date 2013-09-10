$(document).ready(function () {
    'use strict';

    var booksTable = new BooksTableManager($('table.books-list'), $('div.languages-container'));
    booksTable.init();
});
