<?php
// Capture the POST data from the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $merchant_id = '123456789';  // Replace with your actual merchant ID
    $order_id = $_POST['order_id'];     // The order ID from the form
    $amount = $_POST['amount'];         // Amount from the form
    $currency = 'NPR';                  // Currency type (Nepalese Rupee)
    $return_url = 'https://thankyounepaltrip.com/payment-response'; // Your response URL

    // Prepare the POST data to send to the payment gateway
    $data = [
        'merchant_id' => $merchant_id,
        'order_id' => $order_id,
        'amount' => $amount,
        'currency' => $currency,
        'return_url' => $return_url
    ];

    // Send the data to the gateway (handle the POST request)
    $response = post_to_gateway('https://paymentgateway.himalayanbank.com/submit', $data);

    // Debugging: Print the response for troubleshooting
    echo '<pre>';
    print_r($response);
    echo '</pre>';

    // Handle the response (Redirect the user to the payment gateway)
    if ($response['status'] == 'success') {
        // If payment submission is successful, redirect the user to the payment page
        header("Location: " . $response['redirect_url']);
        exit();
    } else {
        // If something goes wrong, show an error message
        echo "Error: " . $response['error_message'];
    }
}

// Function to send data to the payment gateway using cURL
function post_to_gateway($url, $data)
{
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
    curl_setopt($ch, CURLOPT_POST, true);           // Send as a POST request
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Attach POST data

    // Optionally enable verbose output for debugging
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);

    // Execute cURL request and get the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        // Return the error if the request fails
        return ['status' => 'error', 'error_message' => curl_error($ch)];
    }

    // Close the cURL session
    curl_close($ch);

    // Debugging: Output verbose info to check what was sent/received
    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);
    echo "<pre>$verboseLog</pre>";

    // Parse and return the response (assuming JSON format)
    return json_decode($response, true);
}
?>