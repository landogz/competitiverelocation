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
                                    <td class="px-6 py-4 text-sm text-gray-500">Full name of the customer</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">email</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Customer's email address</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">phone</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Customer's phone number</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">ext</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Phone extension (optional)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">from_*</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Pickup location details (areacode, zip, state, city)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">to_*</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Delivery location details (areacode, zip, state, city)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">to_city</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">string</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Delivery location details (areacode, zip, state, city)</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">distance</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">numeric</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Distance in miles</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">move_date</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">date</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yes</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Scheduled move date (YYYY-MM-DD)</td>
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
    "name": "Rosalina Emnace",
    "email": "roseyreader529@gmail.com",
    "phone": "2138586575",
    "ext": "",
    "from_areacode": "213",
    "from_zip": "90026",
    "from_state": "CA",
    "from_city": "los angeles",
    "to_areacode": "708",
    "to_zip": "60463",
    "to_state": "IL",
    "to_city": "palos heights",
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
    "message": "Leads created successfully",
    "data": true
}</code></pre>

                    <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Error Response (500 Internal Server Error)</h3>
                    <pre><code class="language-json">{
    "success": false,
    "message": "Failed to create leads",
    "error": "Error message details"
}</code></pre>
                </div>
            </section>

            <!-- Testing Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Testing the API</h2>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-gray-600 mb-4">
                        You can test this API using tools like Postman or curl. Here's an example using curl:
                    </p>
                    <pre><code class="language-bash">curl -X POST https://competitiverelocationcrm.com/api/leads \
    -H "Content-Type: application/json" \
    -d '{
        "name": "Rosalina Emnace",
        "email": "roseyreader529@gmail.com",
        "phone": "2138586575",
        "ext": "",
        "from_areacode": "213",
        "from_zip": "90026",
        "from_state": "CA",
        "from_city": "los angeles",
        "to_areacode": "708",
        "to_zip": "60463",
        "to_state": "IL",
        "to_city": "palos heights",
        "distance": 1731,
        "move_date": "2025-03-21"
    }'</code></pre>
                </div>
            </section>
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