document.addEventListener("DOMContentLoaded", function() {

    // Select all checkbox function
    document.getElementById("selectAll").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll("input[name='selectedID[]']");
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
    
    document.getElementById('statusFilter').addEventListener('change', function() {
        const filterValue = this.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");
    
        rows.forEach(row => {
            const statusCell = row.cells[5];
            if (statusCell) {
                const cellText = statusCell.textContent.toLowerCase();
                if (filterValue === 'all' || cellText === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
});