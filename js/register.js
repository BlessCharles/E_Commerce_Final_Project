$(document).ready(function () {


    $("#register-form").submit(function (e) {
        e.preventDefault();
        

        // Get values using the correct selectors
        let first_name = $("#first_name").val().trim();
        let last_name = $("#last_name").val().trim();
        let email = $("#email").val().trim();
        let phone = $("#phone").val().trim();
        let password = $("#password").val();
        let confirm_password = $("#confirm_password").val();
        let user_type = $("input[name='user_type']:checked").val();

        

        // VALIDATION
        if (!first_name || !last_name || !email || !phone || !password || !user_type) {
            Swal.fire({
                icon: "error",
                title: "Missing fields",
                text: "Please fill in all fields!"
            });
            return;
        }

        // Check password match
        if (password !== confirm_password) {
            Swal.fire({
                icon: "error",
                title: "Password mismatch",
                text: "Passwords do not match!"
            });
            return;
        }

        // Password strength validation
        if (password.length < 6 ||
            !password.match(/[a-z]/) ||
            !password.match(/[A-Z]/) ||
            !password.match(/[0-9]/)) {
            
            Swal.fire({
                icon: "error",
                title: "Weak password",
                text: "Password must be at least 6 characters and include uppercase, lowercase and a number."
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Creating account...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "../actions/register_action.php",
            type: "POST",
            data: {
                first_name: first_name,
                last_name: last_name,
                email: email,
                phone: phone,
                password: password,
                user_type: user_type
            },

            success: function (response) {
                
                
                try {
                    let r = typeof response === 'string' ? JSON.parse(response) : response;

                    if (r.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Account created!",
                            text: r.message || "Registration successful!"
                        }).then(() => {
                            window.location.href = "login.php";
                        });

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: r.message || "Registration failed. Please try again."
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
                
                
                Swal.fire({
                    icon: "error",
                    title: "Server error",
                    text: "Please try again later. Error: " + error
                });
            }
        });
    });

});