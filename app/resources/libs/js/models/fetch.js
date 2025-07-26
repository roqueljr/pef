class Fetch {
// Internal reusable method
static async handleRequest(url, options = {}) {
try {
const response = await fetch(url, options);

// Auto-handle JSON and basic error
const contentType = response.headers.get("content-type");
if (!response.ok) {
throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
}

if (contentType && contentType.includes("application/json")) {
return await response.json();
} else {
return await response.text();
}

} catch (err) {
console.error('Fetch error:', err.message);
return null;
}
}

// GET with optional query params
static async get(url, params = {}) {
const query = new URLSearchParams(params).toString();
const fullUrl = query ? `${url}?${query}` : url;

return await this.handleRequest(fullUrl, {
method: 'GET',
});
}

// POST with JSON body
static async post(url, data = {}) {
return await this.handleRequest(url, {
method: 'POST',
headers: {
'Content-Type': 'application/json',
},
body: JSON.stringify(data),
});
}
}