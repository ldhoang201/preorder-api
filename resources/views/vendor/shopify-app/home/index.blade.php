<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scaffolding</title>
</head>

<body>
    <div id="app"></div>
    <script>
        var scaffoldingEmbeddedData = {
            apiKey: "{{ config('shopify-app.api_key') }}",
            host: "{{ $host }}",
        };
    </script>
    <script type="module" src="http://localhost:5900/src/main.ts">
    </script>

</body>

</html>