<!DOCTYPE html>
<html>
<head>
    <title>Google Auth Callback</title>
</head>
<body>
<script>
    (function() {
        const token = @json($token);
        if (window.opener) {
            window.opener.postMessage({
                status: 'success',
                token: token
            }, '*');
            setTimeout(() => window.close(), 500);
        } else {
            console.log(token);
            document.write("Tidak ada window opener. Silakan tutup halaman ini.");
        }
    })();
</script>
</body>
</html>
