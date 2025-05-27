<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error - Competitive Relocation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            text-align: center;
        }
        .error-icon {
            font-size: 64px;
            color: #dc3545;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <h2 class="mb-4">Payment Error</h2>
            <p class="text-muted">{{ $message ?? 'An error occurred while processing your payment.' }}</p>
            <a href="/" class="btn btn-primary mt-3">Return to Home</a>
        </div>
    </div>
</body>
</html> 