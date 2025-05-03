<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $template->subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
        }
        .email-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .email-content {
            margin-bottom: 20px;
            white-space: pre-line;
            line-height: 1.4 !important;
        }
        .email-content p {
            margin: 0 0 10px 0 !important;
            line-height: 1.4 !important;
        }
        .email-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #666;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1 style="margin: 0; color: #1f2937; font-size: 24px;">{{ $template->subject }}</h1>
        </div>
        <div class="email-content">
            {!! $content !!}
        </div>
        <div class="email-footer">
            <p style="margin: 0;">This email was sent from Competitive Relocation</p>
        </div>
    </div>
</body>
</html> 