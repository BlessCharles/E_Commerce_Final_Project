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
