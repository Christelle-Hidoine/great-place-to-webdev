document.addEventListener('DOMContentLoaded', function () {
    let filterInput = null;
    let rows = null;
    let filterClass = '';

    // Check if the current URL or page path contains 'country'
    if (window.location.href.includes('back/country') || window.location.pathname.includes('back/country')) {
        filterInput = document.getElementById('countryFilter');
        rows = document.querySelectorAll('.country-row');
        filterClass = '.country-name';
    } else if (window.location.href.includes('back/city') || window.location.pathname.includes('back/city')) {
        filterInput = document.getElementById('cityFilter');
        rows = document.querySelectorAll('.city-row');
        filterClass = '.city-name';
    } else if (window.location.href.includes('back/image') || window.location.pathname.includes('back/image')) {
        filterInput = document.getElementById('cityImageFilter');
        rows = document.querySelectorAll('.city-image-row');
        filterClass = '.city-image-name';
    } else {
        filterInput = document.getElementById('cityReviewFilter');
        rows = document.querySelectorAll('.city-review-row');
        filterClass = '.city-review-name';
    }

    // Add input event listener to filter the rows
    filterInput.addEventListener('input', function () {
        const filterText = filterInput.value.trim().toLowerCase();

        rows.forEach(function (row) {
            const nameCell = row.querySelector(filterClass);
            const name = nameCell.textContent.trim().toLowerCase();

            if (name.includes(filterText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
