document.addEventListener("DOMContentLoaded", function() {

    // Select all checkbox function
    const selectAllCheckbox = document.getElementById("selectAll");
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll("input[name='selectedID[]']");
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }
    
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const filterValue = this.value.toLowerCase();
            const rows = document.querySelectorAll("table tbody tr");
    
            rows.forEach(row => {
                const statusCell = row.cells[4];
                if (statusCell) {
                    const cellText = statusCell.textContent.toLowerCase();
                    // If filterValue is "all", show all rows; otherwise, only show matching rows.
                    if (filterValue === 'all' || cellText === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }
});
