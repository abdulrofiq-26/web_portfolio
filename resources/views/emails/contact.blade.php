<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-w-xl; margin: 0 auto; padding: 20px; border: 1px solid #eaeaea; border-radius: 8px; }
        .header { background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .header h3 { margin: 0 0 10px 0; color: #111; }
        .label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: bold; }
        .content { white-space: pre-wrap; background: #fff; padding: 15px; border: 1px solid #eee; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Message from Your Portfolio!</h2>
        <div class="header">
            <p><span class="label">Name:</span> <br> {{ $name }}</p>
            <p><span class="label">Email:</span> <br> <a href="mailto:{{ $email }}">{{ $email }}</a></p>
            <p><span class="label">Subject:</span> <br> {{ $subjectLine }}</p>
        </div>
        <div class="label" style="margin-bottom: 10px;">Message:</div>
        <div class="content">{{ $messageContent }}</div>
    </div>
</body>
</html>
