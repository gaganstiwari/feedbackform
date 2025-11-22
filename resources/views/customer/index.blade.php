<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <style>
        table { width:100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; }
        tr:hover { background-color:#f9f9f9; cursor:pointer; }
        th { background:#f4f4f4; }
        h2 { text-align:center; margin-top:20px; }
    </style>

    <script>
     function openSignedURL(requestid) {
    const baseUrl = "{{ route('feedback.handle') }}";
    const exp = Math.floor(Date.now() / 1000) + 600;
    const secret = "my_super_secret_key";

    const sig = CryptoJS.HmacSHA256(requestid + exp, secret).toString();

    const url = `${baseUrl}?requestid=${requestid}&token=${sig}`;
    window.location.href = url;
}
    </script>
</head>
<body>
    <h2>Customer List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Requestion</th>
            <th>Name</th>
            <th>Phone</th>
            <th>id</th>
            <th>Addess</th>
        </tr>
        @foreach($customers as $customer)
        <tr onclick="openSignedURL('{{ $customer->requestid }}')">
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->requestid }}</td>
            <td>{{ $customer->caller_name }}</td>
            <td>{{ $customer->caller_number }}</td>
            <td>{{ $customer->id_number }}</td>
            <td>{{ $customer->place }}</td>
           
          
        </tr>
        @endforeach
    </table>
</body>
</html>
