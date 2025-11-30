// Get event_id from URL
const urlParams = new URLSearchParams(window.location.search);
const eventId = urlParams.get('event_id');

// Category icons and names
const categoryIcons = {
    'catering': '🍽️',
    'venue': '🏛️',
    'tent': '⛺',
    'photography': '📸',
    'decoration': '🎨',
    'sound': '🔊',
    'transportation': '🚌',
    'miscellaneous': '📋',
    'rental': '🎪',
    'music': '🎵',
    'makeup': '💄'
};

const categoryNames = {
    'catering': 'Catering',
    'venue': 'Venue',
    'tent': 'Tent & Chairs',
    'photography': 'Photography',
    'decoration': 'Decoration',
    'sound': 'Sound System',
    'transportation': 'Transportation',
    'miscellaneous': 'Other',
    'rental': 'Rental',
    'music': 'Music',
    'makeup': 'Makeup'
};

// Load collab data on page load
document.addEventListener('DOMContentLoaded', function() {
    if (!eventId) {
        alert('No event ID provided');
        window.location.href = 'budget_input.php';
        return;
    }

    loadCollabData();
});

// Load collaboration data from server
async function loadCollabData() {
    try {
        const response = await fetch(`../actions/collab_action.php?action=getCollabData&event_id=${eventId}`);
        const data = await response.json();

        console.log('Collab Data:', data);

        if (data.status === 'success') {
            renderBudgetSummary(data);
            renderSelectedVendors(data);
            // Contributions remain hardcoded in the PHP file
        } else {
            alert(data.message || 'Error loading collaboration data');
            window.location.href = 'budget_input.php';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load collaboration data');
    }
}

// Render budget summary sidebar
function renderBudgetSummary(data) {
    const totalBudget = parseFloat(data.total_budget);
    const allocations = data.allocations;

    // Update total budget
    document.querySelector('.budget-amount').textContent = `GHS ${formatNumber(totalBudget)}`;

    // Get the container that holds budget items
    const container = document.querySelector('.budget-summary');
    
    // Find the first budget-item to insert before the footer
    const firstBudgetItem = container.querySelector('.budget-item');
    const budgetFooter = container.querySelector('.budget-footer');
    
    if (firstBudgetItem && budgetFooter) {
        // Remove all existing budget items
        const allBudgetItems = container.querySelectorAll('.budget-item');
        allBudgetItems.forEach(item => item.remove());
        
        // Add new items before the footer
        allocations.forEach(allocation => {
            const icon = categoryIcons[allocation.category] || '📦';
            const name = categoryNames[allocation.category] || capitalizeWords(allocation.category);
            
            const itemDiv = document.createElement('div');
            itemDiv.className = 'budget-item';
            itemDiv.innerHTML = `
                <span class="item-label">${icon} ${name}</span>
                <span class="item-amount">GHS ${formatNumber(allocation.allocated_amount)}</span>
            `;
            budgetFooter.parentNode.insertBefore(itemDiv, budgetFooter);
        });
    }

    // Update footer total spent
    const totalSpent = parseFloat(data.total_spent);
    const footerAmountSpan = document.querySelector('.budget-footer span:last-child');
    if (footerAmountSpan) {
        footerAmountSpan.textContent = `GHS ${formatNumber(totalSpent)}`;
    }
}

// Render selected vendors
function renderSelectedVendors(data) {
    const vendors = data.vendors;
    const container = document.querySelector('.vendors-section');
    
    if (!vendors || vendors.length === 0) {
        container.innerHTML = `
            <h2 class="section-header">Selected Vendors</h2>
            <p style="text-align: center; color: #64748b; padding: 40px;">
                No vendors selected yet. Go back to select vendors for your event.
            </p>
        `;
        return;
    }

    let html = '<h2 class="section-header">Selected Vendors</h2>';

    vendors.forEach(vendor => {
        const icon = categoryIcons[vendor.category] || '📦';
        const categoryName = categoryNames[vendor.category] || capitalizeWords(vendor.category);
        
        // Determine status based on booking status
        let statusHtml = '';
        if (vendor.booking_status === 'confirmed') {
            statusHtml = `<div class="vendor-status">✓ Confirmed</div>`;
        } else if (vendor.booking_status === 'pending') {
            statusHtml = `<div class="vendor-status" style="background: #fef3c7; color: #a16207;">⏳ Pending Confirmation</div>`;
        } else {
            statusHtml = `<div class="vendor-status">✓ Selected</div>`;
        }

        html += `
            <div class="vendor-item">
                <div class="vendor-header">
                    <div class="vendor-info">
                        <h4>${escapeHtml(vendor.business_name)}</h4>
                        <p class="vendor-category">${icon} ${categoryName}</p>
                    </div>
                    <div class="vendor-price">GHS ${formatNumber(vendor.amount)}</div>
                </div>
                ${statusHtml}
                <div class="vendor-votes">
                    <button class="vote-btn" onclick="voteVendor(${vendor.booking_id}, 'yes')">
                        👍 Approve <strong>0</strong>
                    </button>
                    <button class="vote-btn" onclick="voteVendor(${vendor.booking_id}, 'no')">
                        👎 Disapprove <strong>0</strong>
                    </button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// Vote for vendor (placeholder - can be implemented later)
function voteVendor(bookingId, vote) {
    console.log(`Voting ${vote} for booking ${bookingId}`);
    
    alert('Voting feature coming soon!');
}

// Helper functions
function formatNumber(num) {
    return parseFloat(num).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function capitalizeWords(str) {
    return str.replace(/\b\w/g, l => l.toUpperCase());
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}