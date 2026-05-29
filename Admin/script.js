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
        // If it's already using the custom confirmation, skip it
        if (link.getAttribute('onclick') && link.getAttribute('onclick').includes('confirmDeletion')) return;

        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            
            showConfirmation(
                '🗑️ Confirm Deletion',
                'Are you sure you want to delete this item? This action cannot be undone.',
                '🗑️',
                () => {
                    window.location.href = url;
                },
                () => {
                    // Cancelled
                }
            );
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

// --- Custom Confirmation Modal Logic ---
function showConfirmation(title, message, icon, onConfirm, onCancel) {
    const overlay = document.getElementById('confirmOverlay');
    if (!overlay) {
        if (confirm(message)) onConfirm();
        else if (onCancel) onCancel();
        return;
    }
    
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmIcon').textContent = icon;
    
    overlay.classList.add('active');
    
    const confirmBtn = document.getElementById('confirmOk');
    const cancelBtn = document.getElementById('confirmCancel');
    
    // Clone to remove previous event listeners
    const newConfirmBtn = confirmBtn.cloneNode(true);
    const newCancelBtn = cancelBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
    
    newConfirmBtn.addEventListener('click', function() {
        overlay.classList.remove('active');
        if (onConfirm) onConfirm();
    });
    
    newCancelBtn.addEventListener('click', function() {
        overlay.classList.remove('active');
        if (onCancel) onCancel();
    });
}

// --- Generic Deletion Confirmation ---
function confirmDeletion(e, url, title = '🗑️ Confirm Deletion', message = 'Are you sure you want to delete this item? This action cannot be undone.') {
    if (e) e.preventDefault();
    
    showConfirmation(
        title,
        message,
        '🗑️',
        () => {
            window.location.href = url;
        },
        () => {
            // Cancelled
        }
    );
    return false;
}

// --- Global Logout Confirmation ---
function confirmLogout(e) {
    e.preventDefault();
    const logoutUrl = e.currentTarget.href;
    
    showConfirmation(
        '🚪 Admin Logout',
        'Are you sure you want to end your current session and logout?',
        '🚪',
        () => {
            // Show a quick success bar before redirecting
            const toast = document.createElement('div');
            toast.className = 'admin-toast';
            toast.style.borderColor = '#3b82f6';
            toast.style.background = '#eff6ff';
            toast.style.color = '#1d4ed8';
            toast.innerHTML = `
                <div class="toast-icon-check" style="background:#3b82f6;"><i class="fas fa-sign-out-alt"></i></div>
                <div class="toast-message">Logging you out securely...</div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.classList.add('visible'), 10);
            
            setTimeout(() => {
                window.location.href = logoutUrl;
            }, 800);
        },
        () => {
            // Optional: console.log('Logout cancelled');
        }
    );
    return false;
}

// --- Global Message Bar (Toast) ---
function showMessageBar(message, type = 'success', redirectUrl = null) {
    const toast = document.createElement('div');
    toast.className = `admin-toast ${type}`;
    
    let icon = '<i class="fas fa-check"></i>';
    if (type === 'error') icon = '<i class="fas fa-exclamation-triangle"></i>';
    if (type === 'warning') icon = '<i class="fas fa-exclamation-circle"></i>';
    if (type === 'info') icon = '<i class="fas fa-info-circle"></i>';
    
    toast.innerHTML = `
        <div class="toast-icon">${icon}</div>
        <div class="toast-message">${message}</div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('visible'), 10);
    
    const dismiss = () => {
        toast.classList.remove('visible');
        setTimeout(() => {
            toast.remove();
            if (redirectUrl) {
                location.href = redirectUrl;
            }
        }, 400);
    };
    
    // Auto dismiss after 4s (longer for errors)
    const duration = type === 'error' ? 5000 : 3500;
    setTimeout(dismiss, duration);
}

// Auto-initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}