<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear LocalStorage on Page Load</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>LocalStorage Clear Page</h1>
    <p>When this page loads, the localStorage will be cleared.</p>

    <script>
        $(document).ready(function () {
            localStorage.clear();
            alert('localStorage has been cleared.');
        });
    </script>
</body>
</html>