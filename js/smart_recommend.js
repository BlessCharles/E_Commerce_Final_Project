// Get event_id from URL
const urlParams = new URLSearchParams(window.location.search);
const eventId = urlParams.get('event_id');

// Category icon mapping
const categoryIcons = {
    'catering': '🍽️',
    'venue': '🏛️',
    'tent': '⛺',
    'photography': '📸',
    'decoration': '🎨',
    'sound': '🔊',
    'transportation': '🚌',
    'miscellaneous': '📋'
};

// Category display names
const categoryNames = {
    'catering': 'Catering Services',
    'venue': 'Venue',
    'tent': 'Tent & Chairs',
    'photography': 'Photography Services',
    'decoration': 'Decoration',
    'sound': 'Sound System',
    'transportation': 'Transportation',
    'miscellaneous': 'Miscellaneous'
};

// Category colors for breakdown bars
const categoryColors = {
    'catering': '#ef4444',
    'venue': '#3b82f6',
    'tent': '#8b5cf6',
    'photography': '#10b981',
    'decoration': '#f59e0b',
    'sound': '#ec4899',
    'transportation': '#06b6d4',
    'miscellaneous': '#94a3b8'
};

// Store selected vendors
let selectedVendors = {};

// Load recommendations on page load
document.addEventListener('DOMContentLoaded', function() {
    if (!eventId) {
        alert('No event ID provided');
        window.location.href = 'budget_input.php';
        return;
    }

    loadRecommendations();
});

// Load recommendations from server
async function loadRecommendations() {
    try {
        const response = await fetch(`../actions/recommendation_action.php?action=getRecommendations&event_id=${eventId}`);
        const data = await response.json();

        console.log('API Response:', data); // Debug log

        if (data.status === 'success') {
            // Hide loading state
            document.getElementById('loadingState').style.display = 'none';
            
            // Show content
            document.querySelector('.budget-tracker').style.display = 'block';
            document.querySelector('.content-grid').style.display = 'grid';
            
            // Render all sections
            renderBudgetTracker(data);
            renderBudgetBreakdown(data);
            renderVendorRecommendations(data);
        } else {
            // Hide loading
            document.getElementById('loadingState').style.display = 'none';
            
            // Show error
            alert(data.message || 'Error loading recommendations');
            window.location.href = 'budget_input.php';
        }
    } catch (error) {
        console.error('Error:', error);
        
        // Hide loading
        document.getElementById('loadingState').style.display = 'none';
        
        // Show error
        alert('Failed to load recommendations. Please check console for details.');
    }
}

// Render budget tracker section
function renderBudgetTracker(data) {
    const totalBudget = parseFloat(data.total_budget);
    const totalAllocated = parseFloat(data.total_allocated);
    const percentage = (totalAllocated / totalBudget) * 100;

    const budgetStatus = percentage <= 100 ? '✓ Within Budget' : '⚠ Over Budget';
    const statusClass = percentage <= 100 ? '' : 'over-budget';

    document.querySelector('.budget-amount').textContent = `GHS ${formatNumber(totalAllocated)}`;
    document.querySelector('.budget-total').textContent = `of GHS ${formatNumber(totalBudget)} budget`;
    document.querySelector('.budget-status').textContent = budgetStatus;
    document.querySelector('.budget-status').className = `budget-status ${statusClass}`;
    document.querySelector('.progress-bar-fill').style.width = `${Math.min(percentage, 100)}%`;
}

// Render budget breakdown sidebar
function renderBudgetBreakdown(data) {
    const container = document.querySelector('.budget-breakdown');
    const totalBudget = parseFloat(data.total_budget);
    
    let html = '<h3 class="breakdown-title">Budget Breakdown</h3>';

    for (const [category, info] of Object.entries(data.recommendations)) {
        const icon = categoryIcons[category] || '📦';
        const color = categoryColors[category] || '#94a3b8';
        const barWidth = (info.percentage / 100) * 200; // Max 200px width

        html += `
            <div class="breakdown-item">
                <div>
                    <div class="breakdown-label">
                        ${icon} ${capitalizeWords(category)}
                    </div>
                    <div class="breakdown-bar" style="width: ${barWidth}px; background: ${color};"></div>
                </div>
                <div class="breakdown-amount">
                    <div class="amount-value">GHS ${formatNumber(info.allocated_amount)}</div>
                    <div class="amount-percentage">${info.percentage}%</div>
                </div>
            </div>
        `;
    }

    // Note: Continue button is now in the PHP file
    container.innerHTML = html;
}


// Render vendor recommendations
function renderVendorRecommendations(data) {
    const container = document.querySelector('.recommendations-section');
    let html = '';

    for (const [category, info] of Object.entries(data.recommendations)) {
        if (!info.vendors || info.vendors.length === 0) {
            // Show message if no vendors found
            html += `
                <div class="category-section" data-category="${category}">
                    <div class="category-header">
                        <div class="category-title">
                            <span class="category-icon">${categoryIcons[category] || '📦'}</span>
                            ${categoryNames[category] || capitalizeWords(category)}
                        </div>
                    </div>
                    <p style="color: #64748b; padding: 20px; text-align: center;">
                        No vendors available in this price range. Try browsing all vendors or adjusting your budget.
                    </p>
                </div>
            `;
            continue;
        }

        const icon = categoryIcons[category] || '📦';
        const displayName = categoryNames[category] || capitalizeWords(category);

        html += `
            <div class="category-section" data-category="${category}">
                <div class="category-header">
                    <div class="category-title">
                        <span class="category-icon">${icon}</span>
                        ${displayName}
                    </div>
                    <!-- Browse All link removed - now in PHP -->
                </div>
                <div class="vendor-grid">
                    ${renderVendorCards(info.vendors, category)}
                </div>
            </div>
        `;
    }

    container.innerHTML = html;
}

// Render vendor cards
function renderVendorCards(vendors, category) {
    return vendors.map(vendor => {
        const isSelected = selectedVendors[category] === vendor.vendor_id;
        const selectBtnClass = isSelected ? 'btn-select btn-selected' : 'btn-select';
        const selectBtnText = isSelected ? '✓ Selected' : 'Select';
        
        // DEBUG: Log vendor image path
        console.log(`Vendor: ${vendor.business_name}, Image Path: "${vendor.image}"`);
        
        // Get vendor image - use uploaded image or fallback to placeholder
        const vendorImage = getVendorImageHTML(vendor.image, category);

        return `
            <div class="vendor-card">
                ${vendorImage}
                <div class="vendor-badge">✓ VERIFIED</div>
                <div class="vendor-name">${escapeHtml(vendor.business_name)}</div>
                <div class="vendor-rating">
                    <span class="stars">⭐ ${vendor.rating || '0.0'}</span>
                    <span>(${vendor.total_reviews || 0} reviews)</span>
                </div>
                <div class="vendor-price">GHS ${formatNumber(vendor.starting_price)}</div>
                <div class="vendor-actions">
                    <button class="btn-view" onclick="viewVendorDetails(${vendor.vendor_id})">
                        View Details
                    </button>
                    <button class="${selectBtnClass}" onclick="toggleVendorSelection(${vendor.vendor_id}, '${category}', this)">
                        ${selectBtnText}
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

// Get vendor image HTML - returns actual image or fallback
function getVendorImageHTML(imagePath, category) {
    // Clean up the path - remove whitespace and extra '../'
    if (imagePath && imagePath.trim() !== '') {
        imagePath = imagePath.trim();
        
        // Remove leading '../' if it exists (avoid double ../)
        if (imagePath.startsWith('../')) {
            imagePath = imagePath.substring(3);
        }
        
        // Option 1: Relative path (current)
        const imageUrl = `../${imagePath}`;
        
        // Option 2: Absolute path (uncomment if relative doesn't work)
        // const imageUrl = `/${imagePath}`;
        
        console.log(`Final image URL: ${imageUrl}`); // DEBUG
        
        return `
            <div class="vendor-image" style="background: none; padding: 0;">
                <img src="${imageUrl}" 
                     alt="Vendor business" 
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                     onerror="console.error('Failed to load image: ${imageUrl}'); this.parentElement.innerHTML='${getVendorIcon(category)}'">
            </div>
        `;
    } else {
        // No image - show category icon fallback
        console.log(`No image for category: ${category}, showing icon`); // DEBUG
        return `<div class="vendor-image">${getVendorIcon(category)}</div>`;
    }
}

// Toggle vendor selection
function toggleVendorSelection(vendorId, category, button) {
    if (selectedVendors[category] === vendorId) {
        // Deselect
        delete selectedVendors[category];
        button.classList.remove('btn-selected');
        button.textContent = 'Select';
    } else {
        // Deselect previous vendor in same category
        const categorySection = button.closest('.category-section');
        const prevBtn = categorySection.querySelector('.btn-selected');
        if (prevBtn) {
            prevBtn.classList.remove('btn-selected');
            prevBtn.textContent = 'Select';
        }

        // Select new vendor
        selectedVendors[category] = vendorId;
        button.classList.add('btn-selected');
        button.textContent = '✓ Selected';
    }

    console.log('Selected vendors:', selectedVendors);
}

// View vendor details
function viewVendorDetails(vendorId) {
    window.location.href = `vendor_details.php?vendor_id=${vendorId}&event_id=${eventId}`;
}

// Proceed to review page
function proceedToReview() {
    if (Object.keys(selectedVendors).length === 0) {
        alert('Please select at least one vendor before continuing');
        return;
    }

    // Store selections in sessionStorage
    sessionStorage.setItem('selectedVendors', JSON.stringify(selectedVendors));
    
    // Redirect to review page
    window.location.href = `review_booking.php?event_id=${eventId}`;
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

function getVendorIcon(category) {
    const icons = {
        'catering': '👨‍🍳',
        'venue': '🏛️',
        'tent': '⛺',
        'photography': '📷',
        'decoration': '🎨',
        'sound': '🔊',
        'transportation': '🚗',
        'miscellaneous': '📦'
    };
    return icons[category] || '🏢';
}