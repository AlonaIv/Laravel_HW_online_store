import './bootstrap'

$(document).ready(function() {
    $(document).on('change', '.star', function() {
        $(this).parents('.poststars').submit();
    });
});
