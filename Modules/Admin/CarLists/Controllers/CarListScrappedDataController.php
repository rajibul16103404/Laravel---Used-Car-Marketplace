<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;
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

class CarListScrappedDataController extends Controller
{
    public function index()
    {
        try {
            $response = Http::get("https://api.milltech.ai/api/read-json/qatarliving/all_cars.json");
            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch JSON data.'], 400);
            }

            $cars = $response->json();
            if (!is_array($cars)) {
                return response()->json(['error' => 'Invalid JSON format.'], 400);
            }

            $insertedCars = [];
            foreach ($cars as $car) {
                $make = $this->getOrCreateMake($car['make_id'] ?? null);
                $model = $this->getOrCreateModel($car['model_id'] ?? null);
                $color = $this->getOrCreateColor($car['color_id'] ?? null);
                $year = $this->getOrCreateYear($car['year_id'] ?? null);
                $transmission = $this->getOrCreateTransmission($car['transmission_id'] ?? null);
                $fuel_type = $this->getOrCreateFuelType($car['fuel_type_id'] ?? null);
                $engine_size = $this->getOrCreateEngineSize($car['engine_size_id'] ?? null);
                $doors = $this->getOrCreateDoors($car['door_id'] ?? null);
                $cylinders = $this->getOrCreateCylinders($car['cylinder_id'] ?? null);
                $heading = $this->extractHeadingFromUrl($car['url']);
                $photo_links = json_encode($car['images'] ?? []);
                
                $user_id = null;
                if($car['source'] === "qatarliving") {
                    $user = Auth::select('id')->where('email', 'qal@demo.com')->first();
                    $user_id = $user->id ?? null;
                }

                $carlist = Carlist::create([
                    'heading' => $heading,
                    'price' => $car['price'] ?? null,
                    'miles' => $car['mileage'] ?? null,
                    'exterior_color' => $color,
                    'seller_type' => $car['seller_type_id'] ?? null,
                    'photo_links' => $photo_links,
                    'dealer_id' => $user_id,
                    'year' => $year,
                    'make' => $make,
                    'model' => $model,
                    'transmission' => $transmission,
                    'fuel_type' => $fuel_type,
                    'engine_size' => $engine_size,
                    'doors' => $doors,
                    'cylinders' => $cylinders,
                    'created_at' => $car['created_at'] ?? null,
                    'updated_at' => $car['updated_at'] ?? null
                ]);

                $insertedCars[] = $carlist;
            }
            return response()->json(['message' => 'Car listings added successfully', 'data' => $insertedCars], 201);
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

    private function getOrCreateEngineSize($engineSizeValue) {
        return $engineSizeValue ? EngineSize::firstOrCreate(['name' => $engineSizeValue])->id : null;
    }

    private function getOrCreateDoors($doorCount) {
        return $doorCount ? Door::firstOrCreate(['name' => $doorCount])->id : null;
    }

    private function getOrCreateCylinders($cylinderCount) {
        return $cylinderCount ? Cylinder::firstOrCreate(['name' => $cylinderCount])->id : null;
    }

    private function extractHeadingFromUrl($url) {
        $parts = explode('/', $url);
        $lastPart = end($parts);
        $heading = substr($lastPart, strpos($lastPart, '_') + 1);
        return ucwords(str_replace('_', ' ', $heading));
    }
}
