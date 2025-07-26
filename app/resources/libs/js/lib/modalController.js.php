<script>
var allowClose = false;
$('.filter-menu').on('hide.bs.dropdown', function(e) {
    if (allowClose) {
        allowClose = false;
        return true;
    }
    return false;
});

$('.filter-menu .dropdown-toggle').on('click', function() {
    if ($(this).parent().hasClass('open')) {
        allowClose = true;
    }
});
</script>