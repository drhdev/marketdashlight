// config.js

// Retry intervals in milliseconds for incremental backoff
const retryIntervals = [15000, 30000, 45000, 60000];

// Fallback data in case of API failure
const fallbackData = {
    symbol: "SPY",
    price: "534.76",
    percentChange: "1.21",
    timestamp: "2024-10-28 16:04:23"
};

// Fields to extract from the API response
const apiFields = {
    symbol: "symbol",
    price: "close",
    percentChange: "percentage_diff",
    timestamp: "datetime"
};
