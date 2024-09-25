<?php
$apiKey = "rzp_test_js2MEhBCKiH8p0"; // Replace with your Razorpay API key
$orderId = uniqid('OID'); // Generate a unique order ID for this transaction

// Make sure to handle the scenario where the `orderId` is not set properly
if (!isset($orderId)) {
    die('Error: Order ID is not set');
}

// More processing can go here if needed

?>
        <form action="https://www.example.com/success/" method="POST">
            <script
                src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="<?php echo $apiKey; ?>"
                data-amount="<?php echo $amount * 100; ?>" <!-- Amount in paise -->
                data-currency="INR"
                data-id="<?php echo $orderId; ?>" <!-- Unique order ID -->
                data-buttontext="Pay with Razorpay"
                data-name="Metro Empire"
                data-description="Hotel Booking System"
                data-image="https://example.com/your_logo.jpg"
                data-prefill.name="<?php echo htmlspecialchars($_POST['name']); ?>"
                data-theme.color="#F37254"
            ></script>
            <input type="hidden" custom="Hidden Element" name="hidden"/>
        </form>
    </>
