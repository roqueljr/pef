<script>
    let lastScrollTop = 0;
    let headerVisible = true;

    window.addEventListener('scroll', function() {
    let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // Check if the user is scrolling down and not at the top of the page
    if (currentScrollTop > lastScrollTop && currentScrollTop > 0) {
    // Scrolling down, hide the header
    if (headerVisible) {
    document.getElementById('head').style.display = 'none';
    headerVisible = false;
}
} else {
    // Scrolling up or at the top, show the header
    if (!headerVisible) {
    document.getElementById('head').style.display = 'block';
    headerVisible = true;
}
}

    lastScrollTop = currentScrollTop;
});
</script>
<script>
    // Scroll to Top Function
    function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

    // Show/Hide Scroll to Top Button based on scroll position
    window.onscroll = function() {
    const scrollBtn = document.getElementById('scrollBtn');
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    scrollBtn.style.display = 'block';
} else {
    scrollBtn.style.display = 'none';
}
};
</script>