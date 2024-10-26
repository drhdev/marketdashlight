// Import retry intervals, fallback data, and API fields from config.js
async function updateData(retryCount = 0) {
    try {
        const response = await fetch('/api_proxy.php');
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        if (!data || !data[0]) throw new Error('Invalid API data format');

        // Dynamically extract fields based on config.js
        const symbol = data[0][apiFields.symbol] || fallbackData.symbol;
        const price = data[0][apiFields.price] || fallbackData.price;
        const percentChange = (data[0][apiFields.percentChange] || fallbackData.percentChange).toFixed(2);
        const timestamp = new Date(data[0][apiFields.timestamp] || fallbackData.timestamp).toLocaleString();

        displayData(symbol, price, percentChange, timestamp, false);
    } catch (error) {
        console.error(`Attempt ${retryCount + 1}: ${error.message}`);
        if (retryCount < retryIntervals.length) {
            setTimeout(() => updateData(retryCount + 1), retryIntervals[retryCount]);
        } else {
            // Display fallback data if retries fail
            displayData(
                fallbackData.symbol,
                fallbackData.price,
                fallbackData.percentChange,
                fallbackData.timestamp,
                true
            );
        }
    }
}

// Function to display data in the HTML elements
function displayData(symbol, price, percentChange, timestamp, isError) {
    const dataDisplay = document.getElementById('data-display');
    const messageDisplay = document.getElementById('message-display');
    const timestampDisplay = document.getElementById('timestamp-display');
    
    let colorClass = 'black-text';
    if (percentChange > 0) colorClass = 'green-text';
    else if (percentChange < 0) colorClass = 'red-text';

    // Display data with color coding
    dataDisplay.innerHTML = `
        Symbol: <span class="${colorClass}">${symbol}</span> |
        Price: $${price} |
        Change: <span class="${colorClass}">${percentChange}%</span>
    `;

    // Generate a message based on percent change
    let message = '';
    const percent = parseFloat(percentChange);
    if (percent > 1.1) message = "It looks like a great day in the markets!";
    else if (percent > 0.3) message = "It looks like a good day in the markets...";
    else if (percent > -0.3) message = "It looks like nothing really happens in the markets today...";
    else if (percent > -1.1) message = "It looks like a bad day in the markets...";
    else message = "It looks like a really bad day in the markets...";

    messageDisplay.innerHTML = message;

    // Display timestamp or error message
    if (isError) {
        timestampDisplay.innerHTML = "ERROR: API CALL FAILED - USING DEFAULT VALUES!!!";
        timestampDisplay.classList.add("error");
    } else {
        timestampDisplay.innerHTML = `Last updated: ${timestamp}`;
        timestampDisplay.classList.remove("error");
    }
}

// Initial API call when the page loads
updateData(0);
