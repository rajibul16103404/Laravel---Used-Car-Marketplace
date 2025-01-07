<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Modules\Admin\Body_Subtype\Models\BodySubType;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\Category\Models\Category;
use Modules\Admin\City_Mpg\Models\CityMpg;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;
use Modules\Admin\Color\InteriorColor\Models\InteriorColor;
use Modules\Admin\Color\Models\Color;
use Modules\Admin\Condition\Models\Condition;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Doors\Models\Door;
use Modules\Admin\Drive_Type\Models\Drive_type;
use Modules\Admin\DriveTrain\Models\DriveTrain;
use Modules\Admin\Engine\Models\Engine;
use Modules\Admin\Engine_Block\Models\EngineBlock;
use Modules\Admin\Engine_Size\Models\EngineSize;
use Modules\Admin\Fuel_Type\Models\Fuel_type;
use Modules\Admin\Highway_Mpg\Models\HighwayMpg;
use Modules\Admin\Inventory_Type\Models\InventoryType;
use Modules\Admin\MadeIn\Models\MadeIn;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Overall_Height\Models\OverallHeight;
use Modules\Admin\Overall_Length\Models\OverallLength;
use Modules\Admin\Overall_Width\Models\OverallWidth;
use Modules\Admin\Powertrain_Type\Models\PowertrainType;
use Modules\Admin\Seller_Type\Models\SellerType;
use Modules\Admin\Std_seating\Models\StdSeating;
use Modules\Admin\Transmission\Models\Transmission;
use Modules\Admin\Trim\Models\Trim;
use Modules\Admin\Vehicle_Type\Models\VehicleType;
use Modules\Admin\Version\Models\Version;
use Modules\Admin\Year\Models\Year;

class AllDropController extends Controller
{
    public function index(){
        $exterior = ExteriorColor::where('status',1)->get();
        $interior = InteriorColor::where('status',1)->get();
        $inventory_type = InventoryType::where('status',1)->get();
        $seller_type = SellerType::where('status',1)->get();
        $year = Year::where('status',1)->get();
        $make = Make::where('status',1)->get();
        $model = Carmodel::where('status',1)->get();
        $trim = Trim::where('status',1)->get();
        $version = Version::where('status',1)->get();
        $body_type = Body_Type::where('status',1)->get();
        $body_subtype = BodySubType::where('status',1)->get();
        $vehicle_type = VehicleType::where('status',1)->get();
        $transmission = Transmission::where('status',1)->get();
        $drivetrain = DriveTrain::where('status',1)->get();
        $fuel_type = Fuel_type::where('status',1)->get();
        $engine = Engine::where('status',1)->get();
        $enginesize = EngineSize::where('status',1)->get();
        $engineblock = EngineBlock::where('status',1)->get();
        $door = Door::where('status',1)->get();
        $cylinder = Cylinder::where('status',1)->get();
        $madein = MadeIn::where('status',1)->get();
        $overallheight = OverallHeight::where('status',1)->get();
        $overalllength = OverallLength::where('status',1)->get();
        $overallwidth = OverallWidth::where('status',1)->get();
        $stdseating = StdSeating::where('status',1)->get();
        $highway_mpg = HighwayMpg::where('status',1)->get();
        $city_mpg = CityMpg::where('status',1)->get();
        $powertraintype = PowertrainType::where('status',1)->get();

        
        return response([
            'exterior_color'=> $exterior,
            'interior_color'=> $interior,
            'inventory_type'=>$inventory_type,
            'seller_type'=>$seller_type,
            'year'=>$year,
            'make'=>$make,
            'model'=>$model,
            'trim'=>$trim,
            'version'=>$version,
            'body_type'=>$body_type,
            'body_subtype'=>$body_subtype,
            'vehicle_type'=>$vehicle_type,
            'transmission'=>$transmission,
            'drivetrain'=>$drivetrain,
            'fuel_type'=>$fuel_type,
            'engine'=>$engine,
            'engine_size'=>$enginesize,
            'engine_block'=>$engineblock,
            'doors'=>$door,
            'cylinders'=>$cylinder,
            'made_in'=>$madein,
            'overall_height'=>$overallheight,
            'overall_length'=>$overalllength,
            'overall_width'=>$overallwidth,
            'std_seating'=>$stdseating,
            'highway_mpg'=>$highway_mpg,
            'city_mpg'=>$city_mpg,
            'powertrain_type'=>$powertraintype,
        ],200);
    }
}
