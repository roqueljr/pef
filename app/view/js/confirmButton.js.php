<script>
    function confirmLogout() {
        var confirmLogout = confirm("Are you sure you want to log out?");
        if (confirmLogout) {
        window.location='/login/logout';
    }
}
</script>