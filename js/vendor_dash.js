document.getElementById("editAccount").addEventListener("click", function() {
    document.getElementById("editModal").style.display = "flex";
});

document.getElementById("deleteAccount").addEventListener("click", function() {
    document.getElementById("deleteModal").style.display = "flex";
});

// Close modal buttons
document.querySelectorAll(".closeModal").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".modal").forEach(m => m.style.display = "none");
    });
});


function acceptBooking(id) {
    fetch("accept_booking.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "booking_id=" + id
    })
    .then(r => r.text())
    .then(() => location.reload());
}

function declineBooking(id) {
    fetch("decline_booking.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "booking_id=" + id
    })
    .then(r => r.text())
    .then(() => location.reload());
}

// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Show the corresponding tab content
            const tabName = this.getAttribute('data-tab');
            const contentToShow = document.getElementById(tabName + '-content');
            if (contentToShow) {
                contentToShow.classList.add('active');
            }
        });
    });
});

// Profile dropdown functionality
document.getElementById("profileBtn").addEventListener("click", () => {
    const menu = document.getElementById("profileDropdown");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
});

// Close dropdown when clicking outside
document.addEventListener("click", function (e) {
    const dropdown = document.getElementById("profileDropdown");
    const button = document.getElementById("profileBtn");

    if (!dropdown.contains(e.target) && !button.contains(e.target)) {
        dropdown.style.display = "none";
    }
});

// Modal functionality
const editModal = document.getElementById('editModal');
const deleteModal = document.getElementById('deleteModal');
const editBtn = document.getElementById('editAccount');
const deleteBtn = document.getElementById('deleteAccount');
const closeBtns = document.querySelectorAll('.closeModal');

// Open edit modal
if (editBtn) {
    editBtn.addEventListener('click', function(e) {
        e.preventDefault();
        editModal.style.display = 'flex';
    });
}

// Open delete modal
if (deleteBtn) {
    deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        deleteModal.style.display = 'flex';
    });
}

// Close modals
closeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        editModal.style.display = 'none';
        deleteModal.style.display = 'none';
    });
});

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    if (e.target === editModal) {
        editModal.style.display = 'none';
    }
    if (e.target === deleteModal) {
        deleteModal.style.display = 'none';
    }
});

// Update booking status function
function updateBookingStatus(bookingId, status) {
    if (confirm(`Are you sure you want to ${status === 'confirmed' ? 'accept' : 'decline'} this booking?`)) {
        // Create form data
        const formData = new FormData();
        formData.append('booking_id', bookingId);
        formData.append('status', status);
        
        // Send AJAX request
        fetch('../actions/update_booking_status.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking status updated successfully!');
                location.reload();
            } else {
                alert('Error updating booking status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}


