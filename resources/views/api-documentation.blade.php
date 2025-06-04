<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-json.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-bold text-gray-900">Leads API Documentation</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Overview Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Overview</h2>
                <p class="text-gray-600 mb-4">
                    This API allows you to create new leads records in the system. It handles customer information,
                    pickup and delivery locations, and other relevant details for the transaction.
                </p>
            </section>

            <!-- Endpoint Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Endpoint</h2>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">POST</span>
                        <code class="text-gray-800">https://competitiverelocationcrm.com/api/leads</code>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Headers</h3>
                        <pre><code class="language-json">{
    "Accept": "application/json",
    "Content-Type": "application/json"
}</code></pre>
                    </div>
                </div>
            </section>

            <!-- Request Body Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Request Body</h2>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Parameters</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">name</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Full name of the customer (max 255 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">email</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Valid email address (max 255 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">phone</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Customer's phone number (max 20 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">ext</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Phone extension (max 10 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">from_areacode</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Pickup location area code</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">from_zip</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Pickup location ZIP code (max 10 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">from_state</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Pickup location state</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">from_city</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Pickup location city (max 255 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">to_areacode</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Delivery location area code</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">to_zip</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Delivery location ZIP code (max 10 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">to_state</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Delivery location state</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">to_city</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Delivery location city (max 255 characters)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">distance</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">numeric</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Distance in miles (must be greater than 0)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">move_date</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">date</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Scheduled move date (YYYY-MM-DD, must be a future date)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Example Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Example Request</h2>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <pre><code class="language-json">{
    "name": "John Smith",
    "email": "john.smith@example.com",
    "phone": "2138586575",
    "ext": "123",
    "from_areacode": "213",
    "from_zip": "90026",
    "from_state": "CA",
    "from_city": "Los Angeles",
    "to_areacode": "708",
    "to_zip": "60463",
    "to_state": "IL",
    "to_city": "Palos Heights",
    "distance": 1731,
    "move_date": "2025-03-21"
}</code></pre>
                </div>
            </section>

            <!-- Response Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Response</h2>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Success Response (201 Created)</h3>
                    <pre><code class="language-json">{
    "success": true,
    "message": "Lead created successfully",
    "data": {
        "transaction_id": "000001",
        "firstname": "John",
        "lastname": "Smith",
        "email": "john.smith@example.com",
        "phone": "2138586575",
        "ext": "123",
        "pickup_location": "213, 90026, CA, Los Angeles",
        "delivery_location": "708, 60463, IL, Palos Heights",
        "distance": 1731,
        "move_date": "2025-03-21"
    }
}</code></pre>

                    <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Error Response (422 Unprocessable Entity)</h3>
                    <pre><code class="language-json">{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "Error message for the field"
        ]
    }
}</code></pre>

                    <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Error Response (500 Internal Server Error)</h3>
                    <pre><code class="language-json">{
    "success": false,
    "message": "Failed to create lead: [error message]"
}</code></pre>
                </div>
            </section>

            <!-- Testing Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Testing the API</h2>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-gray-600 mb-4">
                        You can test this API using various programming languages and tools. Here are some examples:
                    </p>

                    <!-- Tabs -->
                    <div class="mb-4 border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="codeTabs" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg cursor-pointer" id="curl-tab" data-tab-target="curl" type="button" role="tab" aria-controls="curl" aria-selected="true">cURL</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 cursor-pointer" id="php-tab" data-tab-target="php" type="button" role="tab" aria-controls="php" aria-selected="false">PHP</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 cursor-pointer" id="javascript-tab" data-tab-target="javascript" type="button" role="tab" aria-controls="javascript" aria-selected="false">JavaScript</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 cursor-pointer" id="python-tab" data-tab-target="python" type="button" role="tab" aria-controls="python" aria-selected="false">Python</button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div id="codeTabContent">
                        <!-- cURL -->
                        <div class="hidden p-4 rounded-lg bg-gray-50" id="curl" role="tabpanel" aria-labelledby="curl-tab">
                            <div class="relative">
                                <button class="absolute top-2 right-2 px-3 py-1 text-sm text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200" onclick="copyCode('curl-code')">
                                    Copy code
                                </button>
                                <pre><code class="language-bash" id="curl-code">curl -X POST https://competitiverelocationcrm.com/api/leads \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{
        "name": "John Smith",
        "email": "john.smith@example.com",
        "phone": "2138586575",
        "ext": "123",
        "from_areacode": "213",
        "from_zip": "90026",
        "from_state": "CA",
        "from_city": "Los Angeles",
        "to_areacode": "708",
        "to_zip": "60463",
        "to_state": "IL",
        "to_city": "Palos Heights",
        "distance": 1731,
        "move_date": "2025-03-21"
    }'</code></pre>
                            </div>
                        </div>

                        <!-- PHP -->
                        <div class="hidden p-4 rounded-lg bg-gray-50" id="php" role="tabpanel" aria-labelledby="php-tab">
                            <div class="relative">
                                <button class="absolute top-2 right-2 px-3 py-1 text-sm text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200" onclick="copyCode('php-code')">
                                    Copy code
                                </button>
                                <pre><code class="language-php" id="php-code">&lt;?php

$data = [
    'name' => 'John Smith',
    'email' => 'john.smith@example.com',
    'phone' => '2138586575',
    'ext' => '123',
    'from_areacode' => '213',
    'from_zip' => '90026',
    'from_state' => 'CA',
    'from_city' => 'Los Angeles',
    'to_areacode' => '708',
    'to_zip' => '60463',
    'to_state' => 'IL',
    'to_city' => 'Palos Heights',
    'distance' => 1731,
    'move_date' => '2025-03-21'
];

$ch = curl_init('https://competitiverelocationcrm.com/api/leads');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);
print_r($result);</code></pre>
                            </div>
                        </div>

                        <!-- JavaScript -->
                        <div class="hidden p-4 rounded-lg bg-gray-50" id="javascript" role="tabpanel" aria-labelledby="javascript-tab">
                            <div class="relative">
                                <button class="absolute top-2 right-2 px-3 py-1 text-sm text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200" onclick="copyCode('javascript-code')">
                                    Copy code
                                </button>
                                <pre><code class="language-javascript" id="javascript-code">// Using Fetch API
const data = {
    name: 'John Smith',
    email: 'john.smith@example.com',
    phone: '2138586575',
    ext: '123',
    from_areacode: '213',
    from_zip: '90026',
    from_state: 'CA',
    from_city: 'Los Angeles',
    to_areacode: '708',
    to_zip: '60463',
    to_state: 'IL',
    to_city: 'Palos Heights',
    distance: 1731,
    move_date: '2025-03-21'
};

fetch('https://competitiverelocationcrm.com/api/leads', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(result => console.log(result))
.catch(error => console.error('Error:', error));

// Using Axios
axios.post('https://competitiverelocationcrm.com/api/leads', data, {
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})
.then(response => console.log(response.data))
.catch(error => console.error('Error:', error));</code></pre>
                            </div>
                        </div>

                        <!-- Python -->
                        <div class="hidden p-4 rounded-lg bg-gray-50" id="python" role="tabpanel" aria-labelledby="python-tab">
                            <div class="relative">
                                <button class="absolute top-2 right-2 px-3 py-1 text-sm text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200" onclick="copyCode('python-code')">
                                    Copy code
                                </button>
                                <pre><code class="language-python" id="python-code">import requests
import json

data = {
    'name': 'John Smith',
    'email': 'john.smith@example.com',
    'phone': '2138586575',
    'ext': '123',
    'from_areacode': '213',
    'from_zip': '90026',
    'from_state': 'CA',
    'from_city': 'Los Angeles',
    'to_areacode': '708',
    'to_zip': '60463',
    'to_state': 'IL',
    'to_city': 'Palos Heights',
    'distance': 1731,
    'move_date': '2025-03-21'
}

headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
}

response = requests.post(
    'https://competitiverelocationcrm.com/api/leads',
    headers=headers,
    json=data
)

print(response.status_code)
print(response.json())</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Add JavaScript for tab functionality and copy code -->
            <script>
                // Tab functionality
                document.addEventListener('DOMContentLoaded', function() {
                    // Get all tab elements
                    const tabList = document.getElementById('codeTabs');
                    if (!tabList) return; // Exit if tab list doesn't exist

                    const tabs = tabList.querySelectorAll('[data-tab-target]');
                    const tabContents = document.querySelectorAll('[role="tabpanel"]');

                    if (tabs.length === 0 || tabContents.length === 0) return; // Exit if no tabs or content

                    function setActiveTab(tab) {
                        if (!tab) return; // Exit if tab is null

                        const targetId = tab.getAttribute('data-tab-target');
                        if (!targetId) return; // Exit if no target specified

                        const target = document.getElementById(targetId);
                        if (!target) return; // Exit if target doesn't exist

                        // Remove active state from all tabs
                        tabs.forEach(t => {
                            if (t) {
                                t.classList.remove('border-blue-500', 'text-blue-600');
                                t.classList.add('border-transparent');
                                t.setAttribute('aria-selected', 'false');
                            }
                        });
                        
                        // Hide all tab contents
                        tabContents.forEach(content => {
                            if (content) {
                                content.classList.add('hidden');
                            }
                        });
                        
                        // Show selected tab content
                        target.classList.remove('hidden');
                        
                        // Add active state to selected tab
                        tab.classList.remove('border-transparent');
                        tab.classList.add('border-blue-500', 'text-blue-600');
                        tab.setAttribute('aria-selected', 'true');
                    }

                    // Add click event listeners to tabs
                    tabs.forEach(tab => {
                        if (tab) {
                            tab.addEventListener('click', () => {
                                setActiveTab(tab);
                            });
                        }
                    });

                    // Activate first tab by default
                    const firstTab = document.getElementById('curl-tab');
                    if (firstTab) {
                        setActiveTab(firstTab);
                    }
                });

                // Copy code functionality
                function copyCode(elementId) {
                    const codeElement = document.getElementById(elementId);
                    if (!codeElement) return;

                    const text = codeElement.textContent;
                    navigator.clipboard.writeText(text).then(() => {
                        const button = codeElement.parentElement.querySelector('button');
                        if (button) {
                            const originalText = button.textContent;
                            button.textContent = 'Copied!';
                            button.classList.add('bg-green-100', 'text-green-800');
                            button.classList.remove('bg-gray-100', 'text-gray-600');
                            
                            setTimeout(() => {
                                button.textContent = originalText;
                                button.classList.remove('bg-green-100', 'text-green-800');
                                button.classList.add('bg-gray-100', 'text-gray-600');
                            }, 2000);
                        }
                    }).catch(err => {
                        console.error('Failed to copy text: ', err);
                    });
                }
            </script>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-500 text-sm">
                    © 2025 Competitive Relocation CRM API Documentation. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html> 