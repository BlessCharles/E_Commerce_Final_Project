$(document).ready(function () {
    console.log("Login.js loaded");

    $("#login-form").submit(function (e) {
        e.preventDefault();
        console.log("Login form submitted");

        // Get form values
        let email = $("#email").val().trim();
        let password = $("#password").val();
        let remember = $("#remember").is(":checked");

        // Basic validation
        if (!email || !password) {
            Swal.fire({
                icon: "error",
                title: "Missing fields",
                text: "Please enter both email and password!"
            });
            return;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: "error",
                title: "Invalid email",
                text: "Please enter a valid email address!"
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Logging in...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Send AJAX request
        $.ajax({
            url: "../actions/login_action.php",
            type: "POST",
            data: {
                email: email,
                password: password,
                remember: remember ? 1 : 0
            },

            success: function (response) {
                console.log("Response:", response);
                
                try {
                    let r = typeof response === 'string' ? JSON.parse(response) : response;

                    if (r.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Welcome back!",
                            text: r.message || "Login successful!",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect based on user type and redirect_url from response
                            window.location.href = r.redirect_url || "../view/dashboard.php";
                        });

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Login failed",
                            text: r.message || "Invalid email or password."
                        });
                    }
                } catch (e) {
                    console.error("Parse error:", e);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Invalid response from server."
                    });
                }
            },

            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                console.error("Response:", xhr.responseText);
                
                Swal.fire({
                    icon: "error",
                    title: "Server error",
                    text: "Please try again later."
                });
            }
        });
    });

});