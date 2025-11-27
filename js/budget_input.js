// Toggle service items
document.querySelectorAll('.service-item').forEach(item => {
    item.addEventListener('click', function(e) {
        if (e.target.type !== 'checkbox') {
            const checkbox = this.querySelector('.service-checkbox');
            checkbox.checked = !checkbox.checked;
        }
        this.classList.toggle('checked', this.querySelector('.service-checkbox').checked);
    });
});
        
// Format budget input
const budgetInput = document.querySelector('.budget-input');
budgetInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/,/g, '');
    if (!isNaN(value) && value !== '') {
        e.target.value = parseInt(value).toLocaleString();
    }
});


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

