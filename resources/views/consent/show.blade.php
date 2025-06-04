<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Consent - Competitive Relocation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-center mb-6">SMS Consent Form</h1>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('consent.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="+1234567890" required>
                    <p class="mt-1 text-sm text-gray-500">Please include country code (e.g., +1 for US)</p>
                </div>

                <div class="relative flex items-start">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" name="consent" id="consent" 
                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="consent" class="font-medium text-gray-700">I agree to receive SMS messages</label>
                        <p class="text-gray-500">By checking this box, you consent to receive SMS messages from Competitive Relocation. Message and data rates may apply. You can opt-out at any time by replying STOP.</p>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit Consent
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 