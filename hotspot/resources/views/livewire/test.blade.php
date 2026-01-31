<div>
    <button id="checkout-button">Checkout Now</button>

    <script type="text/javascript">
    var checkoutButton = document.getElementById('checkout-button');
    // Example: the payment page will show when the button is clicked
    checkoutButton.addEventListener('click', function () {
        loadJokulCheckout('https://sandbox.doku.com/p-link/p/1D1U'); // Replace it with the response.payment.url you retrieved from the response
    });
    </script>
</div>
