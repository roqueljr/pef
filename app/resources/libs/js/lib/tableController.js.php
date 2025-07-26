<script>
$(function() {
    $('#example1').DataTable({
        "order": [],
        columnDefs: [{
            targets: 'no-sort',
            orderable: false
        }]
    })
    $('#example2').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false,
    })
})
</script>