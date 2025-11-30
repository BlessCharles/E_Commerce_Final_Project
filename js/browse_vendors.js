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

// Modal handlers
document.getElementById("editAccount").addEventListener("click", (e) => {
    e.preventDefault();
    document.getElementById("editModal").style.display = "flex";
});

document.getElementById("deleteAccount").addEventListener("click", (e) => {
    e.preventDefault();
    document.getElementById("deleteModal").style.display = "flex";
});

document.querySelectorAll(".closeModal").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".modal").forEach(modal => {
            modal.style.display = "none";
        });
    });
});

// Search and category filter functionality
const searchBox = document.getElementById("searchBox");
const categoryFilter = document.getElementById("categoryFilter");
const filterForm = document.getElementById("filterForm");
const hiddenSearch = document.getElementById("hiddenSearch");
const hiddenCategory = document.getElementById("hiddenCategory");

// Category filter - immediate submission
categoryFilter.addEventListener("change", () => {
    hiddenCategory.value = categoryFilter.value;
    hiddenSearch.value = searchBox.value; // Preserve search value
    filterForm.submit();
});

// Search with debounce
let searchTimeout;
searchBox.addEventListener("input", () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        hiddenSearch.value = searchBox.value;
        hiddenCategory.value = categoryFilter.value; // Preserve category value
        filterForm.submit();
    }, 800); // 800ms delay for better UX
});

// Prevent form submission on Enter key in search box (let debounce handle it)
searchBox.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        clearTimeout(searchTimeout);
        hiddenSearch.value = searchBox.value;
        hiddenCategory.value = categoryFilter.value;
        filterForm.submit();
    }
});