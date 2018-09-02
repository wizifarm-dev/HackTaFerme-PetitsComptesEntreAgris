import collapse from 'bootstrap/js/dist/collapse';
import $ from 'jquery';
import 'easy-autocomplete';

$('.collapse').collapse({
    'toggle': false
});

function addSelectPlaceholderStyle(select) {
    if ($(select).val() === "") {
        $(select).addClass('placeholder');

        return;
    }

    $(select).removeClass('placeholder');
}

$('select').on('change', function() {
    addSelectPlaceholderStyle(this);
});

$(function() {
    $('select').each(function(index, select) {
        addSelectPlaceholderStyle(select);
    })
});

// some vars need to be accessible globally
global.$ = global.jQuery = $;