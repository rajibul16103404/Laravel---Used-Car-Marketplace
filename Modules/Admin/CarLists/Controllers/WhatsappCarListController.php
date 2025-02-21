<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Body_Subtype\Models\BodySubType;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\City_Mpg\Models\CityMpg;
use Modules\Admin\Color\ExteriorColor\Models\ExteriorColor;
use Modules\Admin\Color\InteriorColor\Models\InteriorColor;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Doors\Models\Door;
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
use Modules\Auth\Mail\VerifyOrder;
use Modules\Auth\Mail\Welcome_mail;
use Modules\Auth\Models\Auth as ModelsAuth;

class WhatsappCarListController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|string',
            'fullName' => 'required|string',
            'phone'=>'required|string',
            'vin' => 'required|string',
            'heading' => 'required|string',
            'price' => 'required|numeric',
            'miles' => 'nullable|string',
            'msrp' => 'nullable|string',
            'data_source' => 'nullable|string',
            'vdp_url' => 'nullable|string',
            'carfax_1_owner' => 'nullable|string',
            'carfax_clean_title' => 'nullable|string',
            'exterior_color' => 'nullable|string',
            'interior_color' => 'nullable|string',
            'base_int_color' => 'nullable|string',
            'base_ext_color' => 'nullable|string',
            'dom' => 'nullable|string',
            'dom_180' => 'nullable|string',
            'dom_active' => 'nullable|string',
            'dos_active' => 'nullable|string',
            'seller_type' => 'nullable|string',
            'inventory_type' => 'nullable|string',
            'stock_no' => 'nullable|string',
            'last_seen_at' => 'nullable|string',
            'last_seen_at_date' => 'nullable|string',
            'scraped_at' => 'nullable|string',
            'scraped_at_date' => 'nullable|string',
            'first_seen_at' => 'nullable|string',
            'first_seen_at_date' => 'nullable|string',
            'first_seen_at_source' => 'nullable|string',
            'first_seen_at_source_date' => 'nullable|string',
            'first_seen_at_mc' => 'nullable|string',
            'first_seen_at_mc_date' => 'nullable|string',
            'ref_price' => 'nullable|string',
            'price_change_percent' => 'nullable|string',
            'ref_price_dt' => 'nullable|string',
            'ref_miles' => 'nullable|string',
            'ref_miles_dt' => 'nullable|string',
            'source' => 'nullable|string',
            'model_code' => 'nullable|string',
            'in_transit' => 'nullable|string',
            'photo_links' => 'nullable|string',
            'year' => 'nullable|string',
            'make' => 'nullable|string',
            'model' => 'nullable|string',
            'trim' => 'nullable|string',
            'version' => 'nullable|string',
            'body_type' => 'nullable|string',
            'body_subtype' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'transmission' => 'nullable|string',
            'drivetrain' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'engine' => 'nullable|string',
            'engine_size' => 'nullable|string',
            'engine_block' => 'nullable|string',
            'doors' => 'nullable|string',
            'cylinders' => 'nullable|string',
            'made_in' => 'nullable|string',
            'overall_height' => 'nullable|string',
            'overall_length' => 'nullable|string',
            'overall_width' => 'nullable|string',
            'std_seating' => 'nullable|string',
            'highway_mpg' => 'nullable|string',
            'city_mpg' => 'nullable|string',
            'powertrain_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }



        $user = ModelsAuth::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

    
        if (!$user) {
            $password = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8));
            try {
                $user = ModelsAuth::create([
                    'name' => $request->fullName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => bcrypt($password),
                    'status' => "inactive"
                ]);
                Mail::to($request->email)->send(new welcome_mail($password));
            } catch (Exception $e) {
                return response()->json(['error' => 'User creation or email failed'], 500);
            }
        }

        $userID = ModelsAuth::where('email', $request->email)
                    ->where('phone', $request->phone)
                    ->first();
                    
        if($userID){
            $dealer = $userID->id;
        }
        else{
            $dealer = $user->id;
        }

        $otp = rand(111111, 999999);


        // Fetch or create `ExteriorColor`
        $exterior_colorData = null;
        if (!empty($request->exterior_color)) {
            $exterior_color = ExteriorColor::firstOrCreate(
                ['name' => $request->exterior_color]
            );
            $exterior_colorData = $exterior_color->id;
        }

        // Fetch or create `InteriorColor`
        $interior_colorData = null;
        if (!empty($request->interior_color)) {
            $interior_color = InteriorColor::firstOrCreate(
                ['name' => $request->interior_color]
            );
            $interior_colorData = $interior_color->id;
        }


        // Fetch or create `InventoryType`
        $inventory_typeData = null;
        if (!empty($request->inventory_type)) {
            $inventory_type = InventoryType::firstOrCreate(
                ['name' => $request->inventory_type]
            );
            $inventory_typeData = $inventory_type->id;
        }


        // Fetch or create `SellerType`
        $seller_typeData = null;
        if (!empty($request->seller_type)) {
            $seller_type = SellerType::firstOrCreate(
                ['name' => $request->seller_type]
            );
            $seller_typeData = $seller_type->id;
        }


        // Fetch or create `Year`
        $yearData = null;
        if (!empty($request->year)) {
            $year = Year::firstOrCreate(
                ['name' => $request->year]
            );
            $yearData = $year->id;
        }


        // Fetch or create `Make`
        $makeData = null;
        if (!empty($request->make)) {
            $make = Make::firstOrCreate(
                ['name' => $request->make]
            );
            $makeData = $make->id;
        }

        // Fetch or create `Model`
        $modelData = null;
        if (!empty($request->model)) {
            $model = Carmodel::firstOrCreate(
                ['name' => $request->model]
            );
            $modelData = $model->id;
        }
        

        // Fetch or create `Trim`
        $trimData = null;
        if (!empty($request->trim)) {
            $trim = Trim::firstOrCreate(
                ['name' => $request->trim]
            );
            $trimData = $trim->id;
        }


        // Fetch or create `Version`
        $versionData = null;
        if (!empty($request->version)) {
            $version = Version::firstOrCreate(
                ['name' => $request->version]
            );
            $versionData = $version->id;
        }


        // Fetch or create `Body_type`
        $body_typeData = null;
        if (!empty($request->body_type)) {
            $body_type = Body_Type::firstOrCreate(
                ['name' => $request->body_type]
            );
            $body_typeData = $body_type->id;
        }


        // Fetch or create `Body_subtype`
        $body_subtypeData = null;
        if (!empty($request->body_subtype)) {
            $body_subtype = BodySubType::firstOrCreate(
                ['name' => $request->body_subtype]
            );
            $body_subtypeData = $body_subtype->id;
        }


        // Fetch or create `Vehicle_type`
        $vehicle_typeData = null;
        if (!empty($request->vehicle_type)) {
            $vehicle_type = VehicleType::firstOrCreate(
                ['name' => $request->vehicle_type]
            );
            $vehicle_typeData = $vehicle_type->id;
        }


        // Fetch or create `Transmission`
        $transmissionData = null;
        if (!empty($request->transmission)) {
            $transmission = Transmission::firstOrCreate(
                ['name' => $request->transmission]
            );
            $transmissionData = $transmission->id;
        }


        // Fetch or create `Drivetrain`
        $drivetrainData = null;
        if (!empty($request->drivetrain)) {
            $drivetrain = DriveTrain::firstOrCreate(
                ['name' => $request->drivetrain]
            );
            $drivetrainData = $drivetrain->id;
        }


        // Fetch or create `Fuel_type`
        $fuel_typeData = null;
        if (!empty($request->fuel_type)) {
            $fuel_type = Fuel_type::firstOrCreate(
                ['name' => $request->fuel_type]
            );
            $fuel_typeData = $fuel_type->id;
        }


        // Fetch or create `Engine`
        $engineData = null;
        if (!empty($request->engine)) {
            $engine = Engine::firstOrCreate(
                ['name' => $request->engine]
            );
            $engineData = $engine->id;
        }


        // Fetch or create `Engine_size`
        $engine_sizeData = null;
        if (!empty($request->engine_size)) {
            $engine_size = EngineSize::firstOrCreate(
                ['name' => $request->engine_size]
            );
            $engine_sizeData = $engine_size->id;
        }


        // Fetch or create `Engine_block`
        $engine_blockData = null;
        if (!empty($request->engine_block)) {
            $engine_block = EngineBlock::firstOrCreate(
                ['name' => $request->engine_block]
            );
            $engine_blockData = $engine_block->id;
        }


        // Fetch or create `Doors`
        $doorsData = null;
        if (!empty($request->doors)) {
            $doors = Door::firstOrCreate(
                ['name' => $request->doors]
            );
            $doorsData = $doors->id;
        }

        // Fetch or create `Cylinders`
        $cylindersData = null;
        if (!empty($request->cylinders)) {
            $cylinders = Cylinder::firstOrCreate(
                ['name' => $request->cylinders]
            );
            $cylindersData = $cylinders->id;
        }


        // Fetch or create `Made_in`
        $made_inData = null;
        if (!empty($request->made_in)) {
            $made_in = MadeIn::firstOrCreate(
                ['name' => $request->made_in]
            );
            $made_inData = $made_in->id;
        }

        // Fetch or create `Overall_height`
        $overall_heightData = null;
        if (!empty($request->overall_height)) {
            $overall_height = OverallHeight::firstOrCreate(
                ['name' => $request->overall_height]
            );
            $overall_heightData = $overall_height->id;
        }


        // Fetch or create `Overall_length`
        $overall_lengthData = null;
        if (!empty($request->overall_length)) {
            $overall_length = OverallLength::firstOrCreate(
                ['name' => $request->overall_length]
            );
            $overall_lengthData = $overall_length->id;
        }


        // Fetch or create `Overall_width`
        $overall_widthData = null;
        if (!empty($request->overall_width)) {
            $overall_width = OverallWidth::firstOrCreate(
                ['name' => $request->overall_width]
            );
            $overall_widthData = $overall_width->id;
        }


        // Fetch or create `Std_seating`
        $std_seatingData = null;
        if (!empty($request->std_seating)) {
            $std_seating = StdSeating::firstOrCreate(
                ['name' => $request->std_seating]
            );
            $std_seatingData = $std_seating->id;
        }


        // Fetch or create `Highway_mpg`
        $highway_mpgData = null;
        if (!empty($request->highway_mpg)) {
            $highway_mpg = HighwayMpg::firstOrCreate(
                ['name' => $request->highway_mpg]
            );
            $highway_mpgData = $highway_mpg->id;
        }


        // Fetch or create `City_mpg`
        $city_mpgData = null;
        if (!empty($request->city_mpg)) {
            $city_mpg = CityMpg::firstOrCreate(
                ['name' => $request->city_mpg]
            );
            $city_mpgData = $city_mpg->id;
        }


        // Fetch or create `Powertrain_type`
        $powertrain_typeData = null;
        if (!empty($request->powertrain_types)) {
            $powertrain_type = PowertrainType::firstOrCreate(
                ['name' => $request->powertrain_type]
            );
            $powertrain_typeData = $powertrain_type->id;
        }

        $car_id = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8));

        $carlist = Carlist::create([
            'car_id' => $car_id,
            'vin' => $request->vin,
            'heading' => $request->heading,
            'price' => $request->price,
            'miles' => $request->miles,
            'msrp' => $request->msrp,
            'data_source' => $request->data_source,
            'vdp_url' => $request->vdp_url,
            'carfax_1_owner' => $request->carfax_1_owner,
            'carfax_clean_title' => $request->carfax_clean_title,
            'exterior_color' => $exterior_colorData,
            'interior_color' => $interior_colorData,
            'base_int_color' => $request->base_int_color,
            'base_ext_color' => $request->base_ext_color,
            'dom' => $request->dom,
            'dom_180' => $request->dom_180,
            'dom_active' => $request->dom_active,
            'dos_active' => $request->dos_active,
            'seller_type' => $seller_typeData,
            'inventory_type' => $inventory_typeData,
            'stock_no' => $request->stock_no,
            'last_seen_at' => $request->last_seen_at,
            'last_seen_at_date' => $request->last_seen_at_date,
            'scraped_at' => $request->scraped_at,
            'scraped_at_date' => $request->scraped_at_date,
            'first_seen_at' => $request->first_seen_at,
            'first_seen_at_date' => $request->first_seen_at_date,
            'first_seen_at_source' => $request->first_seen_at_source,
            'first_seen_at_source_date' => $request->first_seen_at_source_date,
            'first_seen_at_mc' => $request->first_seen_at_mc,
            'first_seen_at_mc_date' => $request->first_seen_at_mc_date,
            'ref_price' => $request->ref_price,
            'price_change_percent' => $request->price_change_percent,
            'ref_price_dt' => $request->ref_price_dt,
            'ref_miles' => $request->ref_miles,
            'ref_miles_dt' => $request->ref_miles_dt,
            'source' => $request->source,
            'model_code' => $request->model_code,
            'in_transit' => $request->in_transit,
            'photo_links' => $request->photo_links,
            'dealer_id' => $dealer,
            'year' => $yearData,
            'make' => $makeData,
            'model' => $modelData,
            'trim' => $trimData,
            'version' => $versionData,
            'body_type' => $body_typeData,
            'body_subtype' => $body_subtypeData,
            'vehicle_type' => $vehicle_typeData,
            'transmission' => $transmissionData,
            'drivetrain' => $drivetrainData,
            'fuel_type' => $fuel_typeData,
            'engine' => $engineData,
            'engine_size' => $engine_sizeData,
            'engine_block' => $engine_blockData,
            'doors' => $doorsData,
            'cylinders' => $cylindersData,
            'made_in' => $made_inData,
            'overall_height' => $overall_heightData,
            'overall_length' => $overall_lengthData,
            'overall_width' => $overall_widthData,
            'std_seating' => $std_seatingData,
            'highway_mpg' => $highway_mpgData,
            'city_mpg' => $city_mpgData,
            'powertrain_type' => $powertrain_typeData,
            'otp'=>$otp,
            'email'=>$request->email,
            'fullName'=>$request->fullName,
            'phone'=>$request->phone,
            'status'=>'inactive'
        ]);


        try {
            Mail::to($request->email)->send(new VerifyOrder($otp));

            return response()->json([
                'message' => 'New List Added Successfully',
                'data' => [
                    'car_id'=>$carlist->id,
                    'heading'=>$carlist->heading,
                    'price'=>$carlist->price,
                    'miles'=>$carlist->miles,
                    'exterior_color'=>$carlist->exterior_color,
                    'interior_color'=>$carlist->interior_color,
                    'inventory_type'=>$carlist->inventory_type,
                    'make'=>$carlist->make,
                    'model'=>$carlist->model,
                    'year'=>$carlist->year,
                    'transmission'=>$carlist->transmission,
                    'drivetrain'=>$carlist->drivetrain,
                    'engine'=>$carlist->engine,
                    'fuel_type'=>$carlist->fuel_type,
                    'vin'=>$carlist->vin
                ],
            ], status: 201);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to send verification email'], 500);
        }
    }

    public function verifyCar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:255',
            'otp' => 'required|numeric|digits:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $checkOtp = Carlist::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->latest('id')
            ->first();
    
        if (!$checkOtp) {
            return response()->json(['error' => 'Invalid OTP or Unauthorized access'], 400);
        }
    
        $checkOtp->update(['otp' => null, 'status' => 'active']);

        $userVerified = ModelsAuth::where('phone', $request->phone)->first();
        if($userVerified){
            $userVerified->update([
                'email_verified_at' => now()
            ]);

            return response(['message'=>'New Car Added Successfully.', 'car_id' => $checkOtp->id]);
        }
        else{
            return response(['message'=>'User not found with this phone number.']);
        }
    }
}
