// Pet Shop Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the dashboard
    initDashboard();
});

function initDashboard() {
    // Confirm before deleting items
    initDeleteConfirmations();
    
    // Form validation
    initFormValidation();
    
    // Table sorting
    initTableSorting();
    
    // Real-time updates
    initRealTimeUpdates();
    
    // Responsive sidebar
    initResponsiveSidebar();
    
    // Dashboard charts (if needed)
    initDashboardCharts();
}

// Delete confirmation
function initDeleteConfirmations() {
    const deleteLinks = document.querySelectorAll('a[onclick*="confirm"], a[href*="delete"]');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
}

// Form validation
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#e74c3c';
                    
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    
                    // Add error message
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('field-error')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'field-error';
                        errorDiv.style.color = '#e74c3c';
                        errorDiv.style.fontSize = '14px';
                        errorDiv.style.marginTop = '5px';
                        errorDiv.textContent = 'This field is required';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.style.borderColor = '';
                    const errorDiv = field.parentNode.querySelector('.field-error');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first invalid field
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    firstInvalidField.focus();
                }
                
                showNotification('Please fill in all required fields.', 'error');
            }
        });
    });
}

// Table sorting functionality
function initTableSorting() {
    const tableHeaders = document.querySelectorAll('table th[data-sort]');
    
    tableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const table = this.closest('table');
            const columnIndex = Array.from(this.parentNode.children).indexOf(this);
            const sortDirection = this.getAttribute('data-sort-direction') || 'asc';
            const newSortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            
            // Update sort direction attribute
            this.setAttribute('data-sort-direction', newSortDirection);
            
            // Sort the table
            sortTable(table, columnIndex, newSortDirection);
            
            // Update UI to show sort direction
            tableHeaders.forEach(h => h.classList.remove('sorted-asc', 'sorted-desc'));
            this.classList.add(`sorted-${newSortDirection}`);
        });
    });
}

function sortTable(table, columnIndex, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        // Try to convert to number if possible
        const aNum = parseFloat(aValue);
        const bNum = parseFloat(bValue);
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        // Otherwise, sort as strings
        return direction === 'asc' 
            ? aValue.localeCompare(bValue) 
            : bValue.localeCompare(aValue);
    });
    
    // Remove existing rows
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Add sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

// Real-time updates
function initRealTimeUpdates() {
    // Update time every minute
    updateClock();
    setInterval(updateClock, 60000);
    
    // Check for new orders every 2 minutes
    setInterval(checkNewOrders, 120000);
}

function updateClock() {
    const clockElement = document.getElementById('current-time');
    if (clockElement) {
        const now = new Date();
        clockElement.textContent = now.toLocaleTimeString();
    }
}

function checkNewOrders() {
    // This would typically make an AJAX call to check for new orders
    console.log('Checking for new orders...');
    // Simulate new order notification
    if (Math.random() > 0.7) {
        showNotification('New order received!', 'success');
    }
}

// Responsive sidebar
function initResponsiveSidebar() {
    const sidebarToggle = document.createElement('button');
    sidebarToggle.innerHTML = '☰';
    sidebarToggle.style.position = 'fixed';
    sidebarToggle.style.top = '15px';
    sidebarToggle.style.left = '15px';
    sidebarToggle.style.zIndex = '1000';
    sidebarToggle.style.padding = '10px';
    sidebarToggle.style.background = '#3498db';
    sidebarToggle.style.color = 'white';
    sidebarToggle.style.border = 'none';
    sidebarToggle.style.borderRadius = '5px';
    sidebarToggle.style.cursor = 'pointer';
    sidebarToggle.style.display = 'none';
    
    document.body.appendChild(sidebarToggle);
    
    // Check screen size and setup toggle
    function checkScreenSize() {
        if (window.innerWidth < 1024) {
            sidebarToggle.style.display = 'block';
            document.querySelector('.sidebar').style.transform = 'translateX(-100%)';
            document.querySelector('.main-content').style.marginLeft = '0';
        } else {
            sidebarToggle.style.display = 'none';
            document.querySelector('.sidebar').style.transform = 'translateX(0)';
            document.querySelector('.main-content').style.marginLeft = '280px';
        }
    }
    
    // Initial check
    checkScreenSize();
    
    // Listen for resize events
    window.addEventListener('resize', checkScreenSize);
    
    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (sidebar.style.transform === 'translateX(-100%)') {
            sidebar.style.transform = 'translateX(0)';
            mainContent.style.marginLeft = '280px';
        } else {
            sidebar.style.transform = 'translateX(-100%)';
            mainContent.style.marginLeft = '0';
        }
    });
}

// Dashboard charts (using Chart.js if available)
function initDashboardCharts() {
    // This would initialize charts if Chart.js is included
    if (typeof Chart !== 'undefined') {
        createSalesChart();
        createProductChart();
    }
}

function createSalesChart() {
    // Placeholder for sales chart
    console.log('Sales chart would be created here');
}

function createProductChart() {
    // Placeholder for product chart
    console.log('Product chart would be created here');
}

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `custom-notification ${type}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.padding = '15px 20px';
    notification.style.borderRadius = '8px';
    notification.style.color = 'white';
    notification.style.zIndex = '10000';
    notification.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.2)';
    notification.style.transform = 'translateX(100%)';
    notification.style.transition = 'transform 0.3s ease';
    
    // Set background color based on type
    const colors = {
        success: '#27ae60',
        error: '#e74c3c',
        warning: '#f39c12',
        info: '#3498db'
    };
    
    notification.style.background = colors[type] || colors.info;
    notification.textContent = message;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}

// Export functions for global use
window.AdminDashboard = {
    showNotification,
    formatCurrency,
    formatDate
};

// Auto-initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}