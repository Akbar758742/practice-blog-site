<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Password Changed Successfully</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body, table, td, a {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    body {
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      font-family: Arial, sans-serif;
    }
    .email-container {
      max-width: 600px;
      margin: auto;
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    .email-header {
      background-color: #2d89ef;
      color: #ffffff;
      text-align: center;
      padding: 20px;
    }
    .email-body {
      padding: 30px;
      color: #333333;
    }
    .email-body p {
      margin-bottom: 15px;
    }
    .info-box {
      background-color: #f0f0f0;
      padding: 15px;
      border-radius: 5px;
      margin-top: 10px;
      margin-bottom: 20px;
    }
    .info-box strong {
      display: inline-block;
      width: 100px;
    }
    .email-footer {
      text-align: center;
      font-size: 12px;
      color: #888888;
      padding: 20px;
    }
    @media (max-width: 600px) {
      .email-body {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="email-header">
      <h2>Password Changed</h2>
    </div>
    <div class="email-body">
      <p>Hi {{ $user->name }},</p>
      <p>Your password was successfully changed. Below are your updated login credentials:</p>

      <div class="info-box">
        <p><strong>Email/username:</strong> {{ $user->email }} or {{ $user->username }}</p>
        <p><strong>Password:</strong> {{ $new_password }}</p>
      </div>

      <p>If you did not make this change, please contact our support team immediately or reset your password.</p>
      <p>Thanks,<br>The YourWebsite Team</p>
    </div>
    <div class="email-footer">
      © {{ date('Y') }} larablog. All rights reserved.
    </div>
  </div>
</body>
</html>
