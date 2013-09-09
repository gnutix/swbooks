$(document).ready(function () {
    'use strict';

    var booksTable = new BooksTable($('table.books-list'));

    booksTable.scaleEraHeaders();
    booksTable.applyAutoRowspan();
});
