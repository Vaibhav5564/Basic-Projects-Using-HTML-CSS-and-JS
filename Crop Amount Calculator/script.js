let isCalculated = false;

// Save rate whenever user types
document.addEventListener("DOMContentLoaded", function () {

    const rateInput = document.getElementById("rate");
    const saveBtn = document.getElementById("saveBtn");

    // Restore saved rate
    const savedRate = localStorage.getItem("cropRate");
    if (savedRate !== null) {
        rateInput.value = savedRate;
    }

    // Disable Save initially
    saveBtn.disabled = true;

    // Save rate whenever it changes
    rateInput.addEventListener("input", function () {
        localStorage.setItem("cropRate", this.value);
    });

    // Prevent save before calculate
    document.querySelector("form").addEventListener("submit", function (e) {

        if (!isCalculated) {
            e.preventDefault();
            alert("Please click Calculate before saving.");
            return;
        }

    });

    // After successful save, clear everything except rate
    const params = new URLSearchParams(window.location.search);

    if (params.get("saved") === "1") {

        document.querySelector("input[name='customer_name']").value = "";
        document.getElementById("length").value = "";
        document.getElementById("breadth").value = "";

        document.getElementById("area").innerHTML = "0.00";
        document.getElementById("amount").innerHTML = "0.00";

        document.getElementById("calculated_area").value = "";
        document.getElementById("display_area").value = "";
        document.getElementById("total_amount").value = "";

        saveBtn.disabled = true;
        isCalculated = false;

        window.history.replaceState({}, document.title, "index.php");
    }

});

function calculate() {

    let rate = parseFloat(document.getElementById("rate").value);
    let length = parseFloat(document.getElementById("length").value);
    let breadth = parseFloat(document.getElementById("breadth").value);

    if (isNaN(rate) || isNaN(length) || isNaN(breadth)) {
        alert("Please fill all fields.");
        return;
    }

    let calculatedArea = (length * breadth) / 2178;
    let displayArea = calculatedArea * 2;
    let amount = calculatedArea * rate;

    document.getElementById("area").innerHTML = displayArea.toFixed(2) + " गुंठे";
    document.getElementById("amount").innerHTML = amount.toFixed(2);

    document.getElementById("calculated_area").value = calculatedArea.toFixed(4);
    document.getElementById("display_area").value = displayArea.toFixed(4);
    document.getElementById("total_amount").value = amount.toFixed(2);

    // Keep latest rate
    localStorage.setItem("cropRate", rate);

    isCalculated = true;
    document.getElementById("saveBtn").disabled = false;
}