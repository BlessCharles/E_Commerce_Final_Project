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


