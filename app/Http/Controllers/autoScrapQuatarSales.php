<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AutoScrapQuatarSales extends Controller
{
    function scrape_qatarsale_data() {
        $base_url = "https://qatarsale.com/ar/products/cars_for_sale?page=";
        $page = 1;
        $cars = [];
        $max_cars_per_page = 36;
        set_time_limit(0); // No time limit
    
        while (true) {
            $url = $base_url . $page;
            echo "Scraping Listing Page: $url\n";
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    
            $html = curl_exec($ch);
            curl_close($ch);
    
            if (!$html) {
                echo "Failed to fetch page $page.\n";
                break;
            }
    
            $dom = new DOMDocument;
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);
    
            // Extract car detail page URLs
            $nodes = $xpath->query('//div[contains(@class, "classic-card-wrapper")]//a[@href]');
            $count = 0;
    
            foreach ($nodes as $node) {
                $carUrl = $node->getAttribute('href');
                if (!empty($carUrl) && !in_array($carUrl, array_column($cars, 'url'))) {
                    $cars[] = ['url' => trim($carUrl)];
                    $count++;
                }
            }
    
            if ($count < $max_cars_per_page) {
                echo "No more cars found. Stopping...\n";
                break;
            }
    
            $page++; // Move to next page

            
        }

        return response([
            'Car'=>$cars,
            'Count'=>count($cars)
        ]);

    }

    public function scrape_qatarsale_data_web(Request $request)
    {
        $base_url = $request->input('url', "https://qatarsale.com/en/products/cars_for_sale?page=");
        $page_limit = $request->input('page_limit', 2);
        $file_path = 'qatarsale_cars.csv';
        
        $cars = [];
        $existingCars = $this->loadExistingData($file_path); // Load existing data

        for ($page = 1; $page <= $page_limit; $page++) {
            $url = $base_url . $page;
            $html = $this->fetchHTML($url);

            if (!$html) {
                continue;
            }

            $dom = new DOMDocument;
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);

            $nodes = $xpath->query('//div[contains(@class, "classic-card-wrapper")]//a[@href]');

            foreach ($nodes as $node) {
                $carUrl = trim($node->getAttribute('href'));

                if (!empty($carUrl) && !isset($existingCars[$carUrl])) {
                    $carData = $this->scrapeCarDetails($carUrl);
                    if ($carData) {
                        $cars[] = $carData;
                        $existingCars[$carUrl] = true; // Mark as scraped
                    }
                }
            }
        }

        $this->storeDataInCsv($cars, $file_path);

        return response()->json([
            'cars' => $cars,
            'count' => count($cars),
            'message' => 'Scraping Completed!'
        ]);
    }

    // private function fetchHTML($url)
    // {
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

    //     $html = curl_exec($ch);
    //     curl_close($ch);

    //     return $html;
    // }

    private function fetchHTML($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");

        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }


    private function scrapeCarDetails($carUrl)
    {
        $html = $this->fetchHTML($carUrl);
        if (!$html) {
            return null;
        }

        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // $imageNode = $xpath->query('//qs-show-product-gallery//img')->item(0);
        // $imageUrl = $imageNode ? trim($imageNode->getAttribute('src')) : '';

        // $images = [];
        // $imageNodes = $xpath->query('//div[contains(@class, "gallery-thumbs")]/div[contains(@class, "swiper-wrapper")]//img');

        // foreach ($imageNodes as $node) {
        //     $imageUrl = trim($node->getAttribute('src'));
        //     if (!empty($imageUrl)) {
        //         $images[] = $imageUrl;
        //     }
        // }

        // // Convert images array to a "|" separated string for CSV storage
        // $imageList = implode(' | ', $images);


        // $images = [];
        // $imageNodes = $xpath->query('//div[contains(@class, "gallery-thumbs")]//div[contains(@class, "swiper-wrapper")]/img');

        // foreach ($imageNodes as $node) {
        //     // Try to get 'src' first, if it's not present, check for 'data-src'
        //     $imageUrl = trim($node->getAttribute('src'));
        //     if (empty($imageUrl)) {
        //         $imageUrl = trim($node->getAttribute('data-src')); // For lazy-loaded images
        //     }

        //     if (!empty($imageUrl)) {
        //         $images[] = $imageUrl;
        //     }
        // }

        // // Convert images array to a "|" separated string for CSV storage
        // $imageList = implode(' | ', $images);

        $images = [];
        $imageNodes = $xpath->query('//qs-show-product-gallery//swiper//div[contains(@class, "swiper-wrapper")]/div/div/img');

        foreach ($imageNodes as $node) {
            $imageUrl = trim($node->getAttribute('src'));

            // If 'src' is empty, check 'data-src' for lazy-loaded images
            if (empty($imageUrl)) {
                $imageUrl = trim($node->getAttribute('data-src'));
            }

            if (!empty($imageUrl)) {
                $images[] = $imageUrl;
            }
        }

        // Convert images array to a "|" separated string for CSV storage
        $imageList = implode(' | ', $images);

        Log::info($imageList);


        // Convert images array to a "|" separated string for CSV storage
        $imageList = implode(' | ', $images);




        

        // $imageList = implode('|', $images); // Store multiple images separated by "|"


        $priceNode = $xpath->query('//div[contains(@class, "product-price")]/p')->item(0);
        $price = $priceNode ? trim($priceNode->nodeValue) : '';

        $titleNode = $xpath->query('//div[contains(@class, "title")]/h1')->item(0);
        $title = $titleNode ? trim($titleNode->nodeValue) : '';

        $details = [];
        $detailNodes = $xpath->query('//div[contains(@class, "details")]//ul/li/p');
        foreach ($detailNodes as $node) {
            $details[] = trim($node->nodeValue);
        }

        $detailList = implode(' | ', $details);

        return [
            'url' => $carUrl,
            'image' => $imageList,
            'price' => $price,
            'title' => $title,
            'details' => $detailList
        ];
    }

    private function storeDataInCsv($cars, $file_path)
    {
        if (empty($cars)) {
            return;
        }

        $header = ['URL', 'Image', 'Price', 'Title', 'Details'];

        $exists = Storage::exists($file_path);
        $csv = fopen(storage_path("app/{$file_path}"), 'a');

        if (!$exists) {
            fputcsv($csv, $header);
        }

        foreach ($cars as $car) {
            fputcsv($csv, $car);
        }

        fclose($csv);
    }

    private function loadExistingData($file_path)
    {
        $existingCars = [];

        if (!Storage::exists($file_path)) {
            return $existingCars;
        }

        $file = fopen(storage_path("app/{$file_path}"), 'r');
        fgetcsv($file); // Skip header

        while (($data = fgetcsv($file)) !== false) {
            $existingCars[$data[0]] = true; // Store URL as key
        }

        fclose($file);
        return $existingCars;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    


}
