<script>
    
    function toggleDropdown(dropdownId) {
        var dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle("show");
    }

    
    function closeDropdowns(event) {
        var dropdownContents = document.querySelectorAll('[class^="dropdown-content"]');
        dropdownContents.forEach(function(dropdownContent) {
            if (!dropdownContent.previousElementSibling.contains(event.target) && dropdownContent.classList.contains('show')) {
                dropdownContent.classList.remove('show');
            }
        });
    }
    
    document.addEventListener('click', closeDropdowns);
</script>