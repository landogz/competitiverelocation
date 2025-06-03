<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Test Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-bold text-gray-900">API Test Form</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form id="apiForm" class="space-y-6">
                    <!-- Customer Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900">Customer Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" value="John Doe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="john.doe@example.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel" name="phone" value="5551234567" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Extension</label>
                                <input type="text" name="ext" value="123" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- From Location -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900">From Location</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Area Code</label>
                                <input type="text" name="from_areacode" value="212" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                <input type="text" name="from_zip" value="10001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" name="from_state" value="NY" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="from_city" value="New York" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                        </div>
                    </div>

                    <!-- To Location -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900">To Location</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Area Code</label>
                                <input type="text" name="to_areacode" value="213" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ZIP Code</label>
                                <input type="text" name="to_zip" value="90001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" name="to_state" value="CA" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="to_city" value="Los Angeles" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900">Additional Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Distance (miles)</label>
                                <input type="number" name="distance" value="2800" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Move Date</label>
                                <input type="date" name="move_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Submit
                        </button>
                    </div>
                </form>

                <!-- Response Section -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Response</h2>
                    <pre id="response" class="bg-gray-100 p-4 rounded-md overflow-x-auto"></pre>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            $('#apiForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {};
                $(this).serializeArray().forEach(item => {
                    formData[item.name] = item.value;
                });

                // Show loading state
                $('#response').text('Sending request...');

                $.ajax({
                    url: '{{ url("/api/leads") }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    crossDomain: true,
                    xhrFields: {
                        withCredentials: false
                    },
                    success: function(response) {
                        $('#response').text(JSON.stringify(response, null, 2));
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Error occurred while processing your request.\n\n';
                        
                        if (xhr.responseJSON) {
                            errorMessage += 'Server Response:\n' + JSON.stringify(xhr.responseJSON, null, 2);
                        } else {
                            errorMessage += 'Status: ' + status + '\nError: ' + error + '\nResponse: ' + xhr.responseText;
                        }
                        
                        $('#response').text(errorMessage);
                        console.error('AJAX Error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                    }
                });
            });
        });
    </script>
</body>
</html> 