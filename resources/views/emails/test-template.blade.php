<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Email Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="content">
        {!! $content !!}
    </div>
    
    <div class="footer">
        <p>This is a test email sent from the email template system.</p>
        <p>Test Data:</p>
        <ul>
            @foreach($testData as $key => $value)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html> 