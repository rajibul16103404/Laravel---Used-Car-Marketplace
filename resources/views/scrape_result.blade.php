<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Car URLs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        .car-list {
            margin-top: 20px;
        }
        .car-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Scraped Cars</h1>
        <p>Total Cars Scraped: <strong>{{ $carCount }}</strong></p>

        @if(count($cars) > 0)
            <div class="car-list">
                @foreach($cars as $car)
                    <div class="car-item">
                        <a href="{{ $car['url'] }}" target="_blank">{{ $car['url'] }}</a>
                    </div>
                @endforeach
            </div>
        @else
            <p>No cars found.</p>
        @endif
    </div>
</body>
</html>
