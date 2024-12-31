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
use Modules\Admin\MadeIn\Models\MadeIn;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Overall_Height\Models\OverallHeight;
use Modules\Admin\Overall_Length\Models\OverallLength;
use Modules\Admin\Overall_Width\Models\OverallWidth;
use Modules\Admin\Powertrain_Type\Models\PowertrainType;
use Modules\Admin\Std_seating\Models\StdSeating;
use Modules\Admin\Transmission\Models\Transmission;
use Modules\Admin\Trim\Models\Trim;
use Modules\Admin\Vehicle_Type\Models\VehicleType;
use Modules\Admin\Version\Models\Version;
use Modules\Admin\Year\Models\Year;

class AllDropController extends Controller
{
    public function index(){
        $exterior = ExteriorColor::all();
        $interior = InteriorColor::all();
        $year = Year::all();
        $make = Make::all();
        $model = Carmodel::all();
        $trim = Trim::all();
        $version = Version::all();
        $body_type = Body_Type::all();
        $body_subtype = BodySubType::all();
        $vehicle_type = VehicleType::all();
        $transmission = Transmission::all();
        $drivetrain = DriveTrain::all();
        $fuel_type = Fuel_type::all();
        $engine = Engine::all();
        $enginesize = EngineSize::all();
        $engineblock = EngineBlock::all();
        $door = Door::all();
        $cylinder = Cylinder::all();
        $madein = MadeIn::all();
        $overallheight = OverallHeight::all();
        $overalllength = OverallLength::all();
        $overallwidth = OverallWidth::all();
        $stdseating = StdSeating::all();
        $highway_mpg = HighwayMpg::all();
        $city_mpg = CityMpg::all();
        $powertraintype = PowertrainType::all();

        
        return response([
            'exterior'=> $exterior,
            'interior'=> $interior,
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
