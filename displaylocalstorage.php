<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Formatted JSON</title>
    <style>
        pre {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            font-size: 1rem;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>Stored Data (Formatted JSON):</h1>
    <pre id="jsonData"></pre>

    <script>
        let storedData = localStorage.getItem('UC1_blockPositions');

        let formattedData = JSON.stringify(JSON.parse(storedData), null, 4);

        document.getElementById('jsonData').textContent = formattedData;
    </script>
</body>
</html>
