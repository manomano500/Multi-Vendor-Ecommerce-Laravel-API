<!-- resources/views/payment-success.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-success text-white">
            Payment Success
        </div>
        <div class="card-body">
            <h5 class="card-title">Thank you for your payment!</h5>
            <p class="card-text">Your payment was successful. Here are the details:</p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Gateway:</strong> {{ request('gateway') }}</li>
                <li class="list-group-item"><strong>Approved:</strong> {{ request('approved') == 1 ? 'Yes' : 'No' }}</li>
                <li class="list-group-item"><strong>Amount:</strong> {{ request('amount') }} {{ request('currency') }}</li>
                <li class="list-group-item"><strong>Invoice No:</strong> {{ request('invoice_no') }}</li>
                <li class="list-group-item"><strong>Transaction ID:</strong> {{ request('transaction_id') }}</li>
            </ul>
            <a href="{{ env('FRONTEND_URL') }}" class="btn btn-primary mt-3">Return to Home</a>
        </div>
    </div>
</div>
</body>
</html>
