<?php
// email_config.php

// SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');  // Your SMTP server
define('SMTP_USERNAME', 'trustingmedicare@gmail.com');  // SMTP username
define('SMTP_PASSWORD', 'tfzz ssah onht ocwj');  // SMTP password
define('SMTP_PORT', 587);  // Typically 587 for TLS, 465 for SSL
define('SMTP_SECURE', 'tls');  // 'tls' or 'ssl'
define('EMAIL_FROM', 'trustingmedicare@gmail.com');
define('EMAIL_FROM_NAME', 'Trust MediCare');

// Password reset link expiration (in seconds)
define('PASSWORD_RESET_EXPIRY', 1800);  // 30 minutes