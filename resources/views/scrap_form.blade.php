<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QatarSale Car Scraper</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .progress { height: 25px; }
        .car-list {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
        .car-item {
            border: 1px solid #ddd;
            padding: 10px;
            background: #fff;
            text-align: center;
        }
        .car-item img { width: 100%; height: 180px; object-fit: cover; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">QatarSale Car Scraper</h1>

        <form id="scrape-form">
            @csrf
            <div class="mb-3">
                <label for="url">Enter Base URL</label>
                <input type="text" id="url" name="url" class="form-control" value="https://qatarsale.com/en/products/cars_for_sale?page=" required>
            </div>
            <button type="submit" class="btn btn-primary" id="scrape-btn">Start Scraping</button>
        </form>

        <div class="progress mt-3 d-none">
            <div class="progress-bar bg-success" role="progressbar" style="width: 0%">0%</div>
        </div>

        <h4 class="text-center mt-2" id="next-fetch-timer">Next scrape in: 5m 0s</h4>

        <h3 class="mt-4">Scraped Cars (<span id="car-count">0</span>)</h3>
        <div class="car-list" id="car-list"></div>
    </div>

    <script>
        $(document).ready(function () {
            let pageStart = 1;
            let pagesPerCycle = 1;  // Scrape 1 page per request
            let waitTime = 300; // 5 minutes in seconds
            let scrapingInProgress = false;
    
            function fetchData() {
                if (scrapingInProgress) return;
                scrapingInProgress = true;
    
                let progress = $(".progress-bar");
                let carList = $("#car-list");
                let carCount = $("#car-count");
    
                $("#scrape-btn").prop("disabled", true);
                $(".progress").removeClass("d-none");
                progress.css("width", "10%").text("Scraping started...");
                carList.empty();
                carCount.text("0");
    
                let url = $("#url").val();
    
                $.post("{{ route('scrape_qatarsale_data_web') }}", {
                    url: url,
                    page_start: pageStart,
                    page_limit: pagesPerCycle,
                    _token: "{{ csrf_token() }}"
                }, function (response) {
                    let cars = response.cars;
                    carCount.text(response.count);
    
                    if (cars.length > 0) {
                        progress.css("width", "100%").text("Scraping Completed!");
                        cars.forEach(car => {
                            let carHtml = `<div class="car-item">
                                <a href="${car.url}" target="_blank">
                                    <img src="${car.image}" alt="${car.title}">
                                </a>
                                <h5>${car.title}</h5>
                                <p>${car.price}</p>
                            </div>`;
                            carList.append(carHtml);
                        });
    
                        // Move to next set of pages
                        pageStart++;
                    }
    
                    $("#scrape-btn").prop("disabled", false);
                    scrapingInProgress = false;
                    startCountdown(waitTime);  // Start countdown AFTER scraping finishes
                });
            }
    
            function startCountdown(duration) {
                let timerDisplay = $("#next-fetch-timer");
                let timeLeft = duration;
    
                function updateTimer() {
                    let minutes = Math.floor(timeLeft / 60);
                    let seconds = timeLeft % 60;
                    timerDisplay.text(`Next scrape in: ${minutes}m ${seconds}s`);
    
                    if (timeLeft > 0) {
                        timeLeft--;
                    } else {
                        clearInterval(timerInterval);
                        fetchData(); // Start next scrape when countdown reaches 0
                    }
                }
    
                updateTimer();
                let timerInterval = setInterval(updateTimer, 1000);
            }
    
            fetchData();  // Initial scrape
        });
    </script>
    
    
    
    
</body>
</html>
