

        // Profile dropdown
        document.getElementById("profileBtn").addEventListener("click", () => {
            const menu = document.getElementById("profileDropdown");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        });

        document.addEventListener("click", function (e) {
            const dropdown = document.getElementById("profileDropdown");
            const button = document.getElementById("profileBtn");
            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.style.display = "none";
            }
        });

        // Payment option selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('.payment-radio').checked = false;
                });
                this.classList.add('selected');
                this.querySelector('.payment-radio').checked = true;
            });
        });
        
        // Payment method selection
        document.querySelectorAll('.method-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Remove booking function
        function removeBooking(bookingId, eventId) {
            if (!confirm('Are you sure you want to remove this vendor?')) {
                return;
            }

            fetch('../actions/remove_booking_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ booking_id: bookingId, event_id: eventId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to remove booking');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        // Initiate payment
        function initiatePayment() {
            const paymentPlan = document.querySelector('input[name="payment-plan"]:checked').value;
            const paymentMethod = document.querySelector('.method-btn.selected').dataset.method;

            // Disable button
            const btn = document.querySelector('.btn-confirm');
            btn.disabled = true;
            btn.textContent = 'Processing...';

            fetch('../actions/paystack_init_transaction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    amount: totalAmount,
                    email: userEmail,
                    event_id: eventId,
                    payment_plan: paymentPlan,
                    payment_method: paymentMethod
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.authorization_url) {
                    // Redirect to Paystack
                    window.location.href = data.authorization_url;
                } else {
                    alert(data.message || 'Failed to initialize payment');
                    btn.disabled = false;
                    btn.textContent = `Confirm Booking & Pay GHS ${totalAmount.toFixed(2)}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                btn.disabled = false;
                btn.textContent = `Confirm Booking & Pay GHS ${totalAmount.toFixed(2)}`;
            });
        }