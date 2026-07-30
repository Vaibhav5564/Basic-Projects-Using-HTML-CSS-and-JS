let isCalculated = false;

document.addEventListener("DOMContentLoaded", function () {

    const rateInput = document.getElementById("rate");
    const form = document.getElementById("cropForm");

    // Restore saved rate
    const savedRate = localStorage.getItem("cropRate");
    if (savedRate) {
        rateInput.value = savedRate;
    }

    // Save rate whenever it changes
    rateInput.addEventListener("input", function () {
        localStorage.setItem("cropRate", this.value);
    });

    // If any value changes, user must calculate again
    ["length", "breadth", "rate"].forEach(id => {

        document.getElementById(id).addEventListener("input", function () {

            isCalculated = false;

            document.getElementById("area").innerHTML = "0.00";
            document.getElementById("amount").innerHTML = "0.00";

            document.getElementById("calculated_area").value = "";
            document.getElementById("display_area").value = "";
            document.getElementById("total_amount").value = "";

        });

    });

    // Prevent saving before calculation
    form.addEventListener("submit", function (e) {

        const area = document.getElementById("calculated_area").value;

        if (!isCalculated || area === "" || parseFloat(area) <= 0) {

            e.preventDefault();

            alert("⚠️ Please calculate the result first before saving the customer.");

            return;
        }

    });

    // Success after saving
    const params = new URLSearchParams(window.location.search);

    if (params.get("saved") === "1") {

        alert("✅ Customer details have been saved successfully!");

        document.querySelector("input[name='customer_name']").value = "";
        document.getElementById("length").value = "";
        document.getElementById("breadth").value = "";

        document.getElementById("area").innerHTML = "0.00";
        document.getElementById("amount").innerHTML = "0.00";

        document.getElementById("calculated_area").value = "";
        document.getElementById("display_area").value = "";
        document.getElementById("total_amount").value = "";

        isCalculated = false;

        // Remove ?saved=1 from URL
        window.history.replaceState({}, document.title, "index.php");
    }

});

function calculate() {

    const rate = parseFloat(document.getElementById("rate").value);
    const length = parseFloat(document.getElementById("length").value);
    const breadth = parseFloat(document.getElementById("breadth").value);

    if (isNaN(rate) || isNaN(length) || isNaN(breadth)) {

        alert("⚠️ Please enter Rate, Length, and Breadth before calculating.");

        return;
    }

    const calculatedArea = (length * breadth) / 2178;
    const displayArea = calculatedArea;
    const amount = calculatedArea * rate;

    document.getElementById("area").innerHTML = displayArea.toFixed(2);
    document.getElementById("amount").innerHTML = amount.toFixed(2);

    document.getElementById("calculated_area").value = calculatedArea.toFixed(4);
    document.getElementById("display_area").value = displayArea.toFixed(4);
    document.getElementById("total_amount").value = amount.toFixed(2);

    localStorage.setItem("cropRate", rate);

    isCalculated = true;
}