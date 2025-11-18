<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f4f4f4;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }
        h2{
            margin-top:20px;
            text-align:center;
        }
    </style>

    <script>
        function openSignedURL(policyNumber) {

            const baseUrl = "http://127.0.0.1:8000/feedback";

            // Expiry time (10 minutes)
            const exp = Math.floor(Date.now() / 1000) + 600;

            // Secret Key (must match Laravel)
            const secret = "my_super_secret_key";

            // Create signature
            const signature = sha256(policyNumber + exp + secret);

            // Build URL
            const url = `${baseUrl}?policy=${policyNumber}&exp=${exp}&sig=${signature}`;

            window.location.href = url;
        }

        // Simple SHA-256 function
        function sha256(str) {
            return CryptoJS.HmacSHA256(str, "my_super_secret_key").toString();
        }
    </script>

    <!-- CryptoJS for hashing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

</head>
<body>

<h2>Customer List</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Policy Number</th>
        <th>Aadhaar</th>
        <th>Phone</th>
        <th>Address</th>
    </tr>

    @foreach ($customers as $customer)
       <tr onclick="window.location='{{ route('feedback.form', ['policy' => $customer->policy_number]) }}'" 
    style="cursor:pointer;">
    <td>{{ $customer->id }}</td>
    <td>{{ $customer->name }}</td>
    <td>{{ $customer->policy_number }}</td>
    <td>{{ $customer->phone }}</td>
    <td>{{ $customer->address }}</td>
</tr>

    @endforeach

</table>

</body>
</html>
