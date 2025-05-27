<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Competitive Relocation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #2ecc71;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
        }

        .payment-container, .success-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .payment-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .payment-content {
                grid-template-columns: 1fr;
            }
        }

        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--background-color);
        }

        .payment-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .payment-details {
            background-color: var(--background-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            height: fit-content;
        }

        .payment-form-container {
            background-color: var(--background-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
        }

        .payment-form-container h4 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            font-weight: 600;
        }

        #payment-element {
            margin: 2rem 0;
            padding: 1.5rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }

        .spinner {
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        .success-container {
            text-align: center;
            display: none;
        }

        .success-icon {
            color: var(--success-color);
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .success-container h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .success-container .lead {
            color: #666;
            margin-bottom: 2rem;
        }

        .amount {
            font-size: 2rem;
            font-weight: 700;
            color: var(--success-color);
            margin: 1rem 0;
        }

        .hidden {
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .payment-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: var(--border-radius);
            background-color: #fff3cd;
            color: #856404;
            display: none;
        }

        .payment-message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .payment-message.success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-container" id="paymentContainer">
            <div class="payment-header">
                <h2>Payment Details</h2>
                <p class="text-muted">Complete your payment securely</p>
            </div>
            
            <div class="payment-content">
                <div class="payment-details">
                    <h4><i class="fas fa-info-circle me-2"></i>Transaction Information</h4>
                    <div class="detail-row">
                        <span class="detail-label">Transaction ID</span>
                        <span class="detail-value">#{{ $transaction->transaction_id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Customer Name</span>
                        <span class="detail-value">{{ $transaction->firstname }} {{ $transaction->lastname }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Contact</span>
                        <span class="detail-value">
                            <div>{{ $transaction->email }}</div>
                            <div>{{ $transaction->phone }}</div>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Service Type</span>
                        <span class="detail-value">
                            @if($hasMovingServices)
                                Moving Services
                            @else
                                {{ implode(', ', $serviceNames) }}
                            @endif
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pickup Location</span>
                        <span class="detail-value">{{ $transaction->pickup_location }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Delivery Location</span>
                        <span class="detail-value">{{ $transaction->delivery_location }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Distance</span>
                        <span class="detail-value">{{ $transaction->miles }} miles</span>
                    </div>
                   
                    @php
                        $crewCount = 0;
                        if (is_array($transaction->services)) {
                            foreach ($transaction->services as $service) {
                                if (isset($service['name']) && $service['name'] === 'MOVING SERVICES') {
                                    $crewCount = $service['no_of_crew'] ?? 0;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div class="detail-row">
                        <span class="detail-label">Crew Members</span>
                        <span class="detail-value">{{ $crewCount }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Subtotal</span>
                        <span class="detail-value">${{ number_format($transaction->subtotal, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Mile Rate</span>
                        <span class="detail-value">${{ number_format($transaction->mile_rate, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Software Fee</span>
                        <span class="detail-value">${{ number_format($transaction->software_fee, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Truck Fee</span>
                        <span class="detail-value">${{ number_format($transaction->truck_fee, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Downpayment</span>
                        <span class="detail-value">${{ number_format($transaction->downpayment, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Grand Total</span>
                        <span class="detail-value">${{ number_format($transaction->grand_total, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Amount</span>
                        <span class="detail-value" style="color: var(--success-color); font-weight: bold;">${{ number_format($amount, 2) }}</span>
                    </div>
                </div>

                <div class="payment-form-container">
                    <h4><i class="fas fa-credit-card me-2"></i>Payment Information</h4>
                    <form id="payment-form">
                        <div id="payment-element">
                            <!-- Stripe Elements will be inserted here -->
                        </div>
                        
                        <button id="submit-button" class="btn btn-primary w-100">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="button-text">Pay Now</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="success-container" id="successContainer">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h2>Payment Successful!</h2>
            <p class="lead">Thank you for your payment. Your transaction has been completed successfully.</p>

            <div class="payment-details">
                <h4><i class="fas fa-receipt me-2"></i>Payment Details</h4>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value">{{ $transaction->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount Paid</span>
                    <span class="amount">${{ number_format($amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ now()->format('F j, Y g:i A') }}</span>
                </div>
            </div>

            <div class="mt-4">
                <p><i class="fas fa-envelope me-2"></i>You will receive a confirmation email shortly.</p>
            </div>
        </div>
    </div>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        let elements;
        let paymentElement;

        // Initialize the payment form
        async function initialize() {
            try {
                const response = await fetch('/payment/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        transaction_id: '{{ $transaction->id }}',
                        payment_method_id: 'pm_card_visa' // Default test card
                    })
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message);
                }

                // Create elements instance with the client secret
                elements = stripe.elements({ clientSecret: data.clientSecret });
                paymentElement = elements.create('payment');
                paymentElement.mount('#payment-element');
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        // Handle form submission
        async function handleSubmit(e) {
            e.preventDefault();
            setLoading(true);

            try {
                const { error, paymentIntent } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: `${window.location.origin}/payment/success`,
                    },
                    redirect: 'if_required'
                });

                if (error) {
                    showMessage(error.message, 'error');
                } else if (paymentIntent) {
                    // Confirm the payment on the server
                    const response = await fetch('/payment/confirm', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            transaction_id: '{{ $transaction->id }}',
                            payment_intent_id: paymentIntent.id
                        })
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        // Hide payment container and show success container
                        document.getElementById('paymentContainer').style.display = 'none';
                        document.getElementById('successContainer').style.display = 'block';
                    } else {
                        showMessage(data.message, 'error');
                    }
                }
            } catch (error) {
                showMessage(error.message, 'error');
            }

            setLoading(false);
        }

        // Helper functions
        function setLoading(isLoading) {
            if (isLoading) {
                document.querySelector('#submit-button').disabled = true;
                document.querySelector('#spinner').classList.remove('hidden');
                document.querySelector('#button-text').classList.add('hidden');
            } else {
                document.querySelector('#submit-button').disabled = false;
                document.querySelector('#spinner').classList.add('hidden');
                document.querySelector('#button-text').classList.remove('hidden');
            }
        }

        function showMessage(messageText, type = 'error') {
            Swal.fire({
                icon: type === 'error' ? 'error' : 'success',
                title: type === 'error' ? 'Error' : 'Success',
                text: messageText,
                confirmButtonColor: type === 'error' ? '#dc3545' : '#28a745',
                confirmButtonText: 'OK'
            });
        }

        // Initialize the payment form when the page loads
        initialize();

        // Add event listener for form submission
        document.querySelector('#payment-form').addEventListener('submit', handleSubmit);
    </script>
</body>
</html> 