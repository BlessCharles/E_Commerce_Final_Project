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

// Attach handler to Continue to Collab button using event delegation
// This works even if the button is added dynamically
document.addEventListener('click', async function(e) {
    // Check if clicked element is the collab button
    const collabBtn = e.target.closest('a[href*="collab_work.php"]');
    
    if (collabBtn) {
        e.preventDefault(); // Prevent immediate navigation
        
        if (Object.keys(selectedVendors).length === 0) {
            alert('Please select at least one vendor before continuing to collaboration');
            return;
        }
        
        // Show loading state
        const originalText = collabBtn.textContent;
        collabBtn.textContent = 'Saving selections...';
        collabBtn.style.pointerEvents = 'none';
        
        // Save selections to database
        const saved = await saveVendorSelections();
        
        if (saved) {
            // Navigate to collab page
            window.location.href = `collab_work.php?event_id=${eventId}`;
        } else {
            // Restore button state
            collabBtn.textContent = originalText;
            collabBtn.style.pointerEvents = 'auto';
            alert('Failed to save vendor selections. Please try again.');
        }
    }
});

// Save vendor selections to database
async function saveVendorSelections() {
    try {
        console.log('Saving vendor selections:', selectedVendors);
        
        const response = await fetch('../actions/recommendation_action.php?action=saveSelections', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                event_id: eventId,
                vendors: selectedVendors
            })
        });
        
        // Check if response is OK
        if (!response.ok) {
            console.error('HTTP error:', response.status, response.statusText);
            return false;
        }
        
        const data = await response.json();
        console.log('Save selections response:', data);
        
        if (data.status === 'success') {
            console.log(`Successfully saved ${data.saved_count} vendor(s)`);
            if (data.errors && data.errors.length > 0) {
                console.warn('Some errors occurred:', data.errors);
            }
            return true;
        } else {
            console.error('Save failed:', data.message);
            if (data.errors) {
                console.error('Errors:', data.errors);
            }
            return false;
        }
    } catch (error) {
        console.error('Error saving selections:', error);
        return false;
    }
}

// Load recommendations from server
async function loadRecommendations() {
    try {
        const response = await fetch(`../actions/recommendation_action.php?action=getRecommendations&event_id=${eventId}`);
        const data = await response.json();

        console.log('API Response:', data);

        if (data.status === 'success') {
            document.getElementById('loadingState').style.display = 'none';
            document.querySelector('.budget-tracker').style.display = 'block';
            document.querySelector('.content-grid').style.display = 'grid';
            
            renderBudgetTracker(data);
            renderBudgetBreakdown(data);
            renderVendorRecommendations(data);
        } else {
            document.getElementById('loadingState').style.display = 'none';
            alert(data.message || 'Error loading recommendations');
            window.location.href = 'budget_input.php';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loadingState').style.display = 'none';
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
        const barWidth = (info.percentage / 100) * 200;

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

    container.innerHTML = html;
}

// Render vendor recommendations
function renderVendorRecommendations(data) {
    const container = document.querySelector('.recommendations-section');
    let html = '';

    for (const [category, info] of Object.entries(data.recommendations)) {
        if (!info.vendors || info.vendors.length === 0) {
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
                </div>
                <div class="vendor-grid">
                    ${renderVendorCards(info.vendors, category, info.allocated_amount)}
                </div>
            </div>
        `;
    }

    container.innerHTML = html;
}

// Render vendor cards
function renderVendorCards(vendors, category, allocatedAmount) {
    return vendors.map(vendor => {
        const isSelected = selectedVendors[category] === vendor.vendor_id;
        const selectBtnClass = isSelected ? 'btn-select btn-selected' : 'btn-select';
        const selectBtnText = isSelected ? '✓ Selected' : 'Select';
        
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
                    <button class="${selectBtnClass}" 
                            onclick="toggleVendorSelection(${vendor.vendor_id}, '${category}', ${vendor.starting_price}, this)">
                        ${selectBtnText}
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

// Get vendor image HTML
function getVendorImageHTML(imagePath, category) {
    if (imagePath && imagePath.trim() !== '') {
        imagePath = imagePath.trim();
        
        if (imagePath.startsWith('../')) {
            imagePath = imagePath.substring(3);
        }
        
        const imageUrl = `../${imagePath}`;
        
        return `
            <div class="vendor-image" style="background: none; padding: 0;">
                <img src="${imageUrl}" 
                     alt="Vendor business" 
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                     onerror="this.parentElement.innerHTML='${getVendorIcon(category)}'">
            </div>
        `;
    } else {
        return `<div class="vendor-image">${getVendorIcon(category)}</div>`;
    }
}

// Toggle vendor selection - UPDATED to store vendor details
function toggleVendorSelection(vendorId, category, price, button) {
    if (selectedVendors[category]?.vendor_id === vendorId) {
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

        // Select new vendor - store vendor_id and price
        selectedVendors[category] = {
            vendor_id: vendorId,
            price: price
        };
        button.classList.add('btn-selected');
        button.textContent = '✓ Selected';
    }

    console.log('Selected vendors:', selectedVendors);
}

// View vendor details
function viewVendorDetails(vendorId) {
    window.location.href = `vendor_details.php?vendor_id=${vendorId}&event_id=${eventId}`;
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