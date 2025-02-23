<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;
use Modules\Admin\Color\InteriorColor\Models\InteriorColor;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Make\Models\Make;
use Modules\Auth\Models\Auth;
use Modules\Admin\Year\Models\Year;
use Modules\Admin\Transmission\Models\Transmission;
use Modules\Admin\FuelType\Models\FuelType;
use Modules\Admin\EngineSize\Models\EngineSize;
use Modules\Admin\Doors\Models\Doors;
use Modules\Admin\Cylinders\Models\Cylinders;
use Modules\Admin\Doors\Models\Door;
use Modules\Admin\Fuel_Type\Models\Fuel_type;

class CarListScrappedDataQatarSaleController extends Controller
{
    private function getLastCounter()
    {
        $logFile = storage_path('logs/car_scraper.log');
        
        if (file_exists($logFile)) {
            $logData = json_decode(file_get_contents($logFile), true);
            return isset($logData['qatarsale']) ? (int)$logData['qatarsale'] : 0;
        }
        
        return 0;
    }
    
    private function storeCounter($counter)
    {
        $logFile = storage_path('logs/car_scraper.log');
        
        $logData = ['qatarsale' => $counter]; // Store counter inside 'qatarliving' key
        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT));
    }
    public function index()
    {
        try {
            $response = Http::get("https://api.milltech.ai/api/read-json/qatarsale/all_cars.json");
            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch JSON data.'], 400);
            }

            $cars = $response->json();
            if (!is_array($cars)) {
                return response()->json(['error' => 'Invalid JSON format.'], 400);
            }

            $counter = $this->getLastCounter();

            $insertedCars = [];
            foreach ($cars as $car) {
                $make = $this->getOrCreateMake($car['Make'] ?? null);
                $model = $this->getOrCreateModel($car['Model'] ?? null);
                $color = $this->getOrCreateColor($car['Color'] ?? null);
                $year = $this->getOrCreateYear($car['Year'] ?? null);
                $transmission = $this->getOrCreateTransmission($car['Gear Type'] ?? null);
                $InteriorColor = $this->getOrCreateInteriorColor($car['Inside Color'] ?? null);
                $body_type = $this->getOrCreateBodyType($car['Type'] ?? null);
                $fuel_type = $this->getOrCreateFuelType($car['Fuel Type'] ?? null);
                $cylinders = $this->getOrCreateCylinders($car['Cylinder'] ?? null);
                $heading = $this->extractHeadingFromUrl($car['url']);
                $photo_links = json_encode($car['images'] ?? []);
                
                $user_id = null;
                $user = Auth::select('id')->where('email', 'qas@demo.com')->first();
                $user_id = $user->id ?? null;
                
                $carlist = Carlist::create([
                    'heading' => $heading,
                    'price' => $car['price'] ?? null,
                    'miles' => $car['Mileage'] ?? null,
                    'scraped_at' => $car['scraped_at'] ?? null,
                    'exterior_color' => $color,
                    'seller_type' => $car['seller_type_id'] ?? null,
                    'photo_links' => $photo_links,
                    'dealer_id' => $user_id,
                    'year' => $year,
                    'make' => $make,
                    'model' => $model,
                    'transmission' => $transmission,
                    'fuel_type' => $fuel_type,
                    'interior_color' => $InteriorColor,
                    'doors' => $body_type,
                    'cylinders' => $cylinders,
                    'created_at' => $car['created_at'] ?? null,
                    'updated_at' => $car['updated_at'] ?? null
                ]);

                $counter++;
                $insertedCars[] = $carlist;
            }
            $this->storeCounter($counter);
            return response()->json(["message" => "{$counter} Car listings added successfully", "data"  => $insertedCars], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.', 'message' => $e->getMessage()], 500);
        }
    }

    private function getOrCreateMake($makeName) {
        return $makeName ? Make::firstOrCreate(['name' => $makeName])->id : null;
    }

    private function getOrCreateModel($modelName) {
        return $modelName ? Carmodel::firstOrCreate(['name' => $modelName])->id : null;
    }

    private function getOrCreateColor($colorName) {
        return $colorName ? ExteriorColor::firstOrCreate(['name' => $colorName])->id : null;
    }

    private function getOrCreateYear($yearValue) {
        return $yearValue ? Year::firstOrCreate(['name' => $yearValue])->id : null;
    }

    private function getOrCreateTransmission($transmissionName) {
        return $transmissionName ? Transmission::firstOrCreate(['name' => $transmissionName])->id : null;
    }

    private function getOrCreateFuelType($fuelTypeName) {
        return $fuelTypeName ? Fuel_type::firstOrCreate(['name' => $fuelTypeName])->id : null;
    }

    private function getOrCreateInteriorColor($engineSizeValue) {
        return $engineSizeValue ? InteriorColor::firstOrCreate(['name' => $engineSizeValue])->id : null;
    }

    private function getOrCreateBodyType($doorCount) {
        return $doorCount ? Door::firstOrCreate(['name' => $doorCount])->id : null;
    }

    private function getOrCreateCylinders($cylinderCount) {
        return $cylinderCount ? Cylinder::firstOrCreate(['name' => $cylinderCount])->id : null;
    }

    private function extractHeadingFromUrl($url) {
        $parts = explode('/', $url);
        $lastPart = end($parts);
    
        // Remove everything after the last hyphen (-)
        $heading = explode('-', $lastPart)[0];
    
        // Replace underscores with spaces
        $heading = str_replace('_', ' ', $heading);
    
        return ucwords($heading);
    }
    
}
