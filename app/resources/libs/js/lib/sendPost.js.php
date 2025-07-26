<script>
function sendPost(page) {
    const form = document.createElement('form');
    form.method = 'post';
    form.style.display = 'none';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'nav';
    input.value = page;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

if (window.history.replaceState) {
    window.history.replaceState(null, null, "/0/rrg");
}
</script>