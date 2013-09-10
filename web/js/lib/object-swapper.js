wrapper('ObjectSwapper', [], function () {
    'use strict';

    return {

        /**
         * Allows to entirely replace an object with another one
         */
        replaceWith: function (object, replacement) {
            var originalState = replacement.clone();

            object.replaceWith(originalState);
            object = originalState;

            return object;
        }
    };
});
