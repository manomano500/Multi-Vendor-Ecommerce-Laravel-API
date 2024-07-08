<!-- redirect.complate-payment.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Complete Payment</title>
    <!-- Include necessary CSS and JS here -->
</head>
<body>
<div class="container">
    <h2>Complete Payment</h2>
    <form id="otp-form" action="{{ route('confirm-payment') }}" method="POST">
        @csrf
        <input type="hidden" name="process_id" value="{{ request()->input('process_id') }}">
        <input type="hidden" name="order_id" value="{{ request()->input('order_id') }}">

        <div class="form-group">
            <label for="otp">Enter OTP:</label>
            <input type="text" id="otp" name="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Confirm Payment</button>
    </form>
</div>

<script>
    document.getElementById('otp-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Fetch form data
        const formData = new FormData(this);

        // Send AJAX request to confirm payment
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Payment successful!');
                    // Redirect or perform other actions
                } else {
                    alert('Payment failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>
</body>
</html>
