<?php
// submit_enquiry.php

// 1. Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

// 2. Collect & sanitize input
function clean_input($value) {
    return trim(strip_tags($value));
}

$full_name       = isset($_POST['full_name']) ? clean_input($_POST['full_name']) : '';
$email           = isset($_POST['email']) ? clean_input($_POST['email']) : '';
$contact_number  = isset($_POST['contact_number']) ? clean_input($_POST['contact_number']) : '';
$area            = isset($_POST['area']) ? clean_input($_POST['area']) : '';
$equipment_type  = isset($_POST['equipment_type']) ? clean_input($_POST['equipment_type']) : '';

// 3. Basic validation
$errors = [];

if ($full_name === '') {
    $errors[] = "Full Name is required.";
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid Email Address is required.";
}
if ($contact_number === '') {
    $errors[] = "Contact Number is required.";
}
if ($area === '') {
    $errors[] = "Area / Location is required.";
}
if ($equipment_type === '') {
    $errors[] = "Equipment Type is required.";
}

// 4. If errors, show simple error page
if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Enquiry Error</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                background: #f7f2ec;
                margin: 0;
                padding: 0;
            }
            .wrap {
                max-width: 600px;
                margin: 40px auto;
                background: #ffffff;
                padding: 24px 20px;
                border-radius: 12px;
                border: 1px solid #e4ded5;
                box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            }
            h1 {
                margin-top: 0;
                color: #705033;
                font-size: 1.4rem;
            }
            ul {
                padding-left: 20px;
                margin-top: 8px;
            }
            a.button {
                display: inline-block;
                margin-top: 16px;
                padding: 8px 16px;
                background: #705033;
                color: #ffffff;
                border-radius: 999px;
                text-decoration: none;
                font-size: 0.9rem;
            }
        </style>
    </head>
    <body>
        <div class="wrap">
            <h1>There was a problem with your enquiry</h1>
            <p>Please check the following and try again:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="javascript:history.back()" class="button">Go Back to Form</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// 5. Prepare email

// Change this to your real email:
$to = 'info@boomliftrentalcbe.com';  // or your Gmail / Hostinger email

$subject = 'New Rental Enquiry – Boom / Scissor Lift (Website)';

// Prevent header injection in email field
$safe_email = str_replace(["\r", "\n"], '', $email);

$message_body = "You have received a new enquiry from the website.\n\n"
              . "Name: {$full_name}\n"
              . "Email: {$email}\n"
              . "Contact Number: {$contact_number}\n"
              . "Area / Location: {$area}\n"
              . "Equipment Type: {$equipment_type}\n\n"
              . "Submitted on: " . date('Y-m-d H:i:s');

$headers = "From: Boomlift Rentals Coimbatore <no-reply@yourdomain.com>\r\n";
$headers .= "Reply-To: {$safe_email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// 6. Send email
$mail_sent = mail($to, $subject, $message_body, $headers);

// 7. Show success / fail page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enquiry Submitted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f7f2ec;
            margin: 0;
            padding: 0;
        }
        .wrap {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            padding: 24px 20px;
            border-radius: 12px;
            border: 1px solid #e4ded5;
            box-shadow: 0 8px 18px rgba(0,0,0,0.08);
            text-align: left;
        }
        h1 {
            margin-top: 0;
            color: #705033;
            font-size: 1.5rem;
        }
        p {
            font-size: 0.95rem;
            color: #292524;
        }
        a.button {
            display: inline-block;
            margin-top: 18px;
            padding: 8px 16px;
            background: #705033;
            color: #ffffff;
            border-radius: 999px;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <?php if ($mail_sent): ?>
            <h1>Thank you for your enquiry</h1>
            <p>
                We’ve received your request for boom / scissor lift rental in Coimbatore.
                Our team will contact you during working hours with availability and pricing.
            </p>
        <?php else: ?>
            <h1>Enquiry submitted, but email failed</h1>
            <p>
                Your enquiry was received, but the email could not be sent automatically.
                Please contact us directly by phone or email:
            </p>
            <p>
                Phone: +91-XXXXXXXXXX<br>
                Email: info@boomliftrentalcbe.com
            </p>
        <?php endif; ?>

        <a href="index.html" class="button">Back to Home</a>
    </div>
</body>
</html>
