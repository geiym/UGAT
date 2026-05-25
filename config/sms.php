<?php
// ============================================================
//  config/sms.php  —  SMS Configuration
//  
//  Configure your SMS provider credentials here.
//  Currently supports: Semaphore API (Philippines)
// ============================================================

// SMS Provider Settings
define('SMS_PROVIDER', 'unisms');  // Options: 'unisms', 'semaphore', 'local_test'

// UniSMS Configuration (Free Unlimited SMS)
// Get your API key from: https://unismsapi.com/
define('UNISMS_API_KEY', 'sk_663e2016-3496-4079-97ab-a73853f04a1b');
define('UNISMS_API_URL', 'https://unismsapi.com/api/sms');

// SEMAPHORE Configuration (Optional Backup)
// Get your API key from: https://semaphore.co/
define('SEMAPHORE_API_KEY', 'b17f95c0dc9289c9d84a21788efe1d41');
define('SEMAPHORE_SENDER_NAME', 'SEMAPHORE');

// SMS Configuration
define('SMS_ENABLED', true);
define('SMS_DEBUG_MODE', false);  // Set to true for testing without sending actual SMS

// Notification Preferences
define('NOTIFICATION_PREFERENCE_DEFAULT', 'sms');  // Options: 'sms', 'email', 'both'
define('ENABLE_DUAL_NOTIFICATIONS', true);  // Enable SMS + Email dual notification system

// Default SMS templates
$SMS_TEMPLATES = [
    'order_placed'          => 'Hi {name}, your UGAT order #{order_id} has been placed. Total: {total}. We will notify you of updates.',
    'order_confirmed'       => 'Hi {name}, your UGAT order #{order_id} has been confirmed and is being processed.',
    'order_preparing'       => 'Hi {name}, your UGAT order #{order_id} is now being prepared for delivery.',
    'order_shipped'         => 'Hi {name}, your UGAT order #{order_id} is out for delivery. Expect it today!',
    'order_delivered'       => 'Hi {name}, your UGAT order #{order_id} has been delivered. Thank you for shopping with UGAT!',
    'order_cancelled'       => 'Hi {name}, your UGAT order #{order_id} has been cancelled. Contact UGAT for assistance.',
    'workshop_enrollment'   => 'Hi {name}, your enrollment in {workshop_name} has been approved. Check the UGAT app for details.',
    'enrollment_rejected'   => 'Hi {name}, your enrollment in {workshop_name} was not approved. Contact UGAT for more information.',
    'workshop_reminder'     => 'Reminder: {workshop_name} is on {date} at {time}. See you there!',
    'certification_issued'  => 'Congratulations {name}! Your certification for {workshop_name} is ready. Check the UGAT app to download it.',
    'admin_alert'           => 'Admin Alert: {message}',
    'payment_received'      => 'Thank you {name}! Your GCash payment of {amount} for order #{order_id} has been received.',
];

/**
 * Get SMS template with substituted values
 * 
 * @param string $template_key
 * @param array $replacements
 * @return string
 */
function getSmsTemplate(string $template_key, array $replacements = []): string
{
    global $SMS_TEMPLATES;
    
    $template = $SMS_TEMPLATES[$template_key] ?? 'No template found.';
    
    foreach ($replacements as $key => $value) {
        $template = str_replace('{' . $key . '}', $value, $template);
    }
    
    return $template;
}
