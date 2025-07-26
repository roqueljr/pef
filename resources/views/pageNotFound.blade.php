<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ Route::assets('PEF_LOGO.png') }}" type="png">
    <title>404 Not Found</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        text-align: center;
    }

    .container {
        max-width: 600px;
        padding: 40px;
    }

    h1 {
        font-size: 72px;
        margin: 0;
        color: #d9534f;
    }

    h2 {
        margin-top: 0;
        font-weight: 400;
    }

    p {
        font-size: 18px;
        color: #777;
    }

    a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>Sorry, the page you are looking for doesn't exist or has been moved.</p>
        <p><a href="#" onclick="window.history.back()">Go back</a></p>
    </div>
</body>

</html>