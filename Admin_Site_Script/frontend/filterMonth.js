document.addEventListener("DOMContentLoaded", function() {
    
    // Select all checkbox function
    const selectAllCheckbox = document.getElementById("selectAll");
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll("input[name='selectedID[]']");
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }
    
    const monthFilter = document.getElementById('monthFilter');
    if (monthFilter) {
        monthFilter.addEventListener('change', function() {
            const filterValue = this.value;
            const rows = document.querySelectorAll("table tbody tr");
            
            rows.forEach(row => {
                // Assuming the Month is in the 2nd column (index 1)
                const monthCell = row.cells[1];
                if (monthCell) {
                    // Month format from the database is "YYYY-MM"
                    const cellText = monthCell.textContent.trim();
                    // Get month part
                    const parts = cellText.split("-");
                    const rowMonth = parts[1]; // index 1 contains the month part
                    
                    if (filterValue === "all" || rowMonth === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }
});
