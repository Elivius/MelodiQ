document.addEventListener("DOMContentLoaded", function() {
    // Add User Function
    const rowData = [
    ];

    const tbody = document.querySelector("tbody");

    // Select all checkbox function
    document.getElementById("selectAll").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll("input[name='selectedID[]']");
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
    
    // Client-side filtering function
    // Make sure your <select> has id="statusFilter"
    document.getElementById('versionFilter').addEventListener('change', function() {
        const filterValue = this.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            const versionCell = row.cells[4]; // Version column (index 4)
            if (versionCell) {
                const cellText = versionCell.textContent.toLowerCase();
                // Show all rows if "All" is selected, otherwise match the version
                if (filterValue === 'all' || cellText === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
});