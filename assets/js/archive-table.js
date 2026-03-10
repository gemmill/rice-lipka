document.addEventListener('DOMContentLoaded', function() {
    console.log('Archive table script loaded');
    
    const table = document.getElementById('projects-table');
    
    // Safety check - make sure table exists
    if (!table) {
        console.error('Projects table not found');
        return;
    }
    
    console.log('Table found:', table);
    
    const headers = table.querySelectorAll('th.sortable');
    const tbody = table.querySelector('tbody');
    
    console.log('Headers found:', headers.length);
    console.log('Tbody found:', tbody);
    
    // Safety check - make sure we have headers and tbody
    if (!headers.length || !tbody) {
        console.error('Table headers or tbody not found');
        return;
    }
    
    let currentSort = { column: 'year', direction: 'desc' };
    
    headers.forEach((header, index) => {
        console.log('Adding click listener to header', index, header.dataset.sort);
        
        header.addEventListener('click', function() {
            console.log('Header clicked:', this.dataset.sort);
            
            const sortType = this.dataset.sort;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            console.log('Rows found for sorting:', rows.length);
            
            // Safety check - make sure we have rows
            if (!rows.length) {
                console.warn('No table rows found');
                return;
            }
            
            // Toggle direction if same column, otherwise default to asc
            if (currentSort.column === sortType) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.direction = 'asc';
                currentSort.column = sortType;
            }
            
            console.log('Sorting by:', sortType, 'direction:', currentSort.direction);
            
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
                
                console.log('Comparing:', aVal, 'vs', bVal);
                
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
            
            console.log('Rows sorted, re-appending to tbody');
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
            
            console.log('Sorting complete');
        });
    });
    
    // Set initial sort indicator
    const yearHeader = table.querySelector('th[data-sort="year"]');
    if (yearHeader) {
        yearHeader.classList.add('sort-desc');
        console.log('Initial sort indicator set on year header');
    }
    
    console.log('Archive table script initialization complete');
});