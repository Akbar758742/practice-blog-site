<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* Ensure consistent rendering */
    body, table, td, a {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    table {
      border-collapse: collapse !important;
    }
    body {
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
      background-color: #f2f4f6;
      font-family: Arial, sans-serif;
    }
    .email-wrapper {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 5px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .email-header {
      background-color: #4a90e2;
      padding: 20px;
      color: #ffffff;
      text-align: center;
    }
    .email-body {
      padding: 30px;
      color: #333333;
    }
    .email-body p {
      margin: 0 0 15px;
    }
    .btn {
      display: inline-block;
      padding: 12px 20px;
      background-color: #4a90e2;
      color: #ffffff !important;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .email-footer {
      text-align: center;
      padding: 20px;
      font-size: 12px;
      color: #999999;
    }
    @media only screen and (max-width: 600px) {
      .email-body {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="email-wrapper">
    <div class="email-header">
      <h2>Reset Your Password</h2>
    </div>
    <div class="email-body">
      <p>Hello,{{ $user->name }}</p>
      <p>You recently requested to reset your password. Click the button below to reset it:</p>
      <p style="text-align: center;">
        <a href="{{ $actionLink }}" target="_blank" class="btn">Reset Password</a>
      </p>
      <p>If you did not request a password reset, you can safely ignore this email.</p>
      <p>Thanks,<br>The YourWebsite Team</p>
    </div>
    <div class="email-footer">
      © {{ date('Y') }} larablog. All rights reserved.
    </div>
  </div>
</body>
</html>
