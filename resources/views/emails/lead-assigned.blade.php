<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: white; border-radius: 8px; padding: 32px;">
        <h2 style="color: #1A1A1A;">New Lead Assigned to You</h2>
        <p style="color: #555;">You have been assigned a new lead in TECHFLOW Lead Management System.</p>

        <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; color: #888;">Name:</td>
                <td style="padding: 8px 0; font-weight: bold;">{{ $lead->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888;">Company:</td>
                <td style="padding: 8px 0;">{{ $lead->company ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888;">Email:</td>
                <td style="padding: 8px 0;">{{ $lead->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888;">Phone:</td>
                <td style="padding: 8px 0;">{{ $lead->phone }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888;">Product:</td>
                <td style="padding: 8px 0;">{{ $lead->product ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #888;">Priority:</td>
                <td style="padding: 8px 0;">{{ ucfirst($lead->priority) }}</td>
            </tr>
        </table>

        <p style="margin-top: 24px; color: #555;">Please log in to your dashboard to view and update this lead.</p>

        <a href="{{ url('/') }}" style="display: inline-block; margin-top: 16px; padding: 12px 24px; background: #F9B933; color: #1A1A1A; text-decoration: none; border-radius: 6px; font-weight: bold;">
    Go to Dashboard
</a>
    </div>
</body>
</html>