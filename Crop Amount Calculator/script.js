let todayRate = 0;

// Save Rate
function setRate() {

    const rate = document.getElementById("rate").value;

    if (rate === "" || rate <= 0) {
        alert("Please enter a valid rate.");
        return;
    }

    todayRate = Number(rate);

    document.querySelector(".rate-box").style.display = "none";
    document.getElementById("calculator").style.display = "block";

}

// Calculate
// Calculate
function calculate() {

    const length = Number(document.getElementById("length").value);
    const breadth = Number(document.getElementById("breadth").value);

    if (length <= 0 || breadth <= 0) {
        alert("Please enter valid length and breadth.");
        return;
    }

    const calculatedArea = (length * breadth) / 2178;
    const area = calculatedArea / 20;
    const amount = calculatedArea * todayRate;

    document.getElementById("rAmount").textContent = amount.toFixed(2);

}