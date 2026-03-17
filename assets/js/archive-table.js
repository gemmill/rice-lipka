document.addEventListener('DOMContentLoaded', function() {
    
    const table = document.getElementById('projects-table');
    
    // Safety check - make sure table exists
    if (!table) {
        return;
    }
    
    const headers = table.querySelectorAll('th.sortable');
    const tbody = table.querySelector('tbody');
    
    // Safety check - make sure we have headers and tbody
    if (!headers.length || !tbody) {
        return;
    }
    
    let currentSort = { column: 'year', direction: 'desc' };
    
    headers.forEach((header, index) => {
        
        header.addEventListener('click', function() {
            
            const sortType = this.dataset.sort;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Safety check - make sure we have rows
            if (!rows.length) {
                return;
            }
            
            // Toggle direction if same column, otherwise default to asc
            if (currentSort.column === sortType) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.direction = 'asc';
                currentSort.column = sortType;
            }
            
            // Remove sort indicators from all headers
            headers.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
            });
            
            // Add sort indicator to current header
            this.classList.add(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');
            
            // Sort rows
            rows.sort((a, b) => {
                let aVal = a.dataset[sortType] || '';
                let bVal = b.dataset[sortType] || '';
                
                // Handle numeric sorting for year
                if (sortType === 'year') {
                    aVal = parseInt(aVal) || 0;
                    bVal = parseInt(bVal) || 0;
                    return currentSort.direction === 'asc' ? aVal - bVal : bVal - aVal;
                }
                
                // Handle text sorting
                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();
                
                if (currentSort.direction === 'asc') {
                    return aVal.localeCompare(bVal);
                } else {
                    return bVal.localeCompare(aVal);
                }
            });
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
            
        });
    });
    
    // Set initial sort indicator
    const yearHeader = table.querySelector('th[data-sort="year"]');
    if (yearHeader) {
        yearHeader.classList.add('sort-desc');
    }
    
});