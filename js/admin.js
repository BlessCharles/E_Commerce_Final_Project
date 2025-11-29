// Tab switching functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding content
                document.getElementById(targetTab + '-content').classList.add('active');
            });
        });
        
        // Approve vendor function
        function approveVendor(vendorId) {
            if (confirm('Are you sure you want to approve and verify this vendor? They will receive an email confirmation.')) {
                // Send AJAX request to approve vendor
                fetch('../actions/approve_vendor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'vendor_id=' + vendorId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Vendor approved successfully! ✓');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                    console.error('Error:', error);
                });
            }
        }
        
        // Reject vendor function
        function rejectVendor(vendorId) {
            const reason = prompt('Please enter reason for rejection (this will be sent to the vendor):');
            if (reason) {
                fetch('../actions/reject_vendor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'vendor_id=' + vendorId + '&reason=' + encodeURIComponent(reason)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Vendor application rejected. Notification sent.');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                    console.error('Error:', error);
                });
            }
        }