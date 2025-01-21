<?php

namespace Modules\Admin\Profile\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Body_Subtype\Models\BodySubType;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\Checkout\Models\Checkout;
use Modules\Admin\Checkout\Models\OrderItems;
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

class ProfileController extends Controller
{
    public function orderList(Request $request)
    {
        $user_id = Auth::id();
        $perPage = $request->input('per_page', 10);

        $data = Checkout::with('carlist')->where('user_id', $user_id)->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'pagination' => [
                'total_count'=>$data->total(),
                'total_page'=>$data->lastPage(),
                'current_page'=>$data->currentPage(),
                'current_page_count'=>$data->count(),
                'next_page' => $data->hasMorePages() ? $data->currentPage()+1 : null,
                'previous_page'=>$data->onFirstPage() ? null : $data->currentPage()
            ],
            'message' => 'Data Retrieved Successfully',
            'data' => $data->items(),
        ],200);
    }

    public function orderItem($order_id)
    {
        // Find all order items by order_id
        $orderItems = OrderItems::where('order_id', $order_id)->get();

        if ($orderItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order items not found',
            ], 404);
        }

        // Prepare arrays to batch fetch related data
        $makeIds = [];
        $modelIds = [];
        $yearIds = [];
        $exteriorColorIds = [];
        $interiorColorIds = [];
        $trimIds = [];
        $versionIds = [];
        $bodyTypeIds = [];
        $bodySubtypeIds = [];
        $vehicleTypeIds = [];
        $transmissionIds = [];
        $drivetrainIds = [];
        $fuelTypeIds = [];
        $engineIds = [];
        $engineSizeIds = [];
        $engineBlockIds = [];
        $doorIds = [];
        $cylinderIds = [];
        $madeInIds = [];
        $overallHeightIds = [];
        $overallLengthIds = [];
        $overallWidthIds = [];
        $stdSeatingIds = [];
        $highwayMpgIds = [];
        $cityMpgIds = [];
        $powertrainTypeIds = [];

        foreach ($orderItems as $orderItem) {
            $items = json_decode($orderItem->items);
            if (json_last_error() === JSON_ERROR_NONE) {
                $makeIds[] = $items->make ?? null;
                $modelIds[] = $items->model ?? null;
                $yearIds[] = $items->year ?? null;
                $exteriorColorIds[] = $items->exterior_color ?? null;
                $interiorColorIds[] = $items->interior_color ?? null;
                $trimIds[] = $items->trim ?? null;
                $versionIds[] = $items->version ?? null;
                $bodyTypeIds[] = $items->body_type ?? null;
                $bodySubtypeIds[] = $items->body_subtype ?? null;
                $vehicleTypeIds[] = $items->vehicle_type ?? null;
                $transmissionIds[] = $items->transmission ?? null;
                $drivetrainIds[] = $items->drivetrain ?? null;
                $fuelTypeIds[] = $items->fuel_type ?? null;
                $engineIds[] = $items->engine ?? null;
                $engineSizeIds[] = $items->engine_size ?? null;
                $engineBlockIds[] = $items->engine_block ?? null;
                $doorIds[] = $items->doors ?? null;
                $cylinderIds[] = $items->cylinders ?? null;
                $madeInIds[] = $items->made_in ?? null;
                $overallHeightIds[] = $items->overall_height ?? null;
                $overallLengthIds[] = $items->overall_length ?? null;
                $overallWidthIds[] = $items->overall_width ?? null;
                $stdSeatingIds[] = $items->std_seating ?? null;
                $highwayMpgIds[] = $items->highway_mpg ?? null;
                $cityMpgIds[] = $items->city_mpg ?? null;
                $powertrainTypeIds[] = $items->powertrain_type ?? null;
            }
        }

        // Fetch related data in batches
        $makes = Make::whereIn('id', $makeIds)->get()->keyBy('id');
        $models = Carmodel::whereIn('id', $modelIds)->get()->keyBy('id');
        $years = Year::whereIn('id', $yearIds)->get()->keyBy('id');
        $interior_colors = InteriorColor::whereIn('id', $interiorColorIds)->get()->keyBy('id');
        $exterior_colors = ExteriorColor::whereIn('id', $exteriorColorIds)->get()->keyBy('id');
        $trims = Trim::whereIn('id', $trimIds)->get()->keyBy('id');
        $versions = Version::whereIn('id', $versionIds)->get()->keyBy('id');
        $body_types = Body_Type::whereIn('id', $bodyTypeIds)->get()->keyBy('id');
        $body_subtypes = BodySubType::whereIn('id', $bodySubtypeIds)->get()->keyBy('id');
        $vehicle_types = VehicleType::whereIn('id', $vehicleTypeIds)->get()->keyBy('id');
        $transmissions = Transmission::whereIn('id', $transmissionIds)->get()->keyBy('id');
        $drivetrains = DriveTrain::whereIn('id', $drivetrainIds)->get()->keyBy('id');
        $fuel_types = Fuel_type::whereIn('id', $fuelTypeIds)->get()->keyBy('id');
        $engines = Engine::whereIn('id', $engineIds)->get()->keyBy('id');
        $engine_sizes = EngineSize::whereIn('id', $engineSizeIds)->get()->keyBy('id');
        $engine_blocks = EngineBlock::whereIn('id', $engineBlockIds)->get()->keyBy('id');
        $doors = Door::whereIn('id', $doorIds)->get()->keyBy('id');
        $cylinders = Cylinder::whereIn('id', $cylinderIds)->get()->keyBy('id');
        $made_ins = MadeIn::whereIn('id', $madeInIds)->get()->keyBy('id');
        $overall_heights = OverallHeight::whereIn('id', $overallHeightIds)->get()->keyBy('id');
        $overall_lengths = OverallLength::whereIn('id', $overallLengthIds)->get()->keyBy('id');
        $overall_widths = OverallWidth::whereIn('id', $overallWidthIds)->get()->keyBy('id');
        $std_seatings = StdSeating::whereIn('id', $stdSeatingIds)->get()->keyBy('id');
        $highway_mpgs = HighwayMpg::whereIn('id', $highwayMpgIds)->get()->keyBy('id');
        $city_mpgs= CityMpg::whereIn('id', $cityMpgIds)->get()->keyBy('id');
        $powertrain_types = PowertrainType::whereIn('id', $powertrainTypeIds)->get()->keyBy('id');

        

        // Decode items and map related names
        $decodedItems = [];
        foreach ($orderItems as $orderItem) {
            $items = json_decode($orderItem->items);
            if (json_last_error() === JSON_ERROR_NONE) {
                $items->make = $makes[$items->make]->name ?? 'Unknown';
                $items->model = $models[$items->model]->name ?? 'Unknown';
                $items->year = $years[$items->year]->name ?? 'Unknown';
                $items->interior_color = $interior_colors[$items->interior_color]->name ?? 'Unknown';
                $items->exterior_color = $exterior_colors[$items->exterior_color]->name ?? 'Unknown';
                $items->trim = $trims[$items->trim]->name ?? 'Unknown';
                $items->version = $versions[$items->version]->name ?? 'Unknown';
                $items->body_type = $body_types[$items->body_type]->name ?? 'Unknown';
                $items->body_subtype = $body_subtypes[$items->body_subtype]->name ?? 'Unknown';
                $items->vehicle_type = $vehicle_types[$items->vehicle_type]->name ?? 'Unknown';
                $items->transmission = $transmissions[$items->transmission]->name ?? 'Unknown';
                $items->drivetrain = $drivetrains[$items->drivetrain]->name ?? 'Unknown';
                $items->fuel_type = $fuel_types[$items->fuel_type]->name ?? 'Unknown';
                $items->engine = $engines[$items->engine]->name ?? 'Unknown';
                $items->engine_size = $engine_sizes[$items->engine_size]->name ?? 'Unknown';
                $items->engine_block = $engine_blocks[$items->engine_block]->name ?? 'Unknown';
                $items->doors = $doors[$items->doors]->name ?? 'Unknown';
                $items->cylinders = $cylinders[$items->cylinders]->name ?? 'Unknown';
                $items->made_in = $made_ins[$items->made_in]->name ?? 'Unknown';
                $items->overall_height = $overall_heights[$items->overall_height]->name ?? 'Unknown';
                $items->overall_length = $overall_lengths[$items->overall_length]->name ?? 'Unknown';
                $items->overall_width = $overall_widths[$items->overall_width]->name ?? 'Unknown';
                $items->std_seating = $std_seatings[$items->std_seating]->name ?? 'Unknown';
                $items->highway_mpg = $highway_mpgs[$items->highway_mpg]->name ?? 'Unknown';
                $items->city_mpg = $city_mpgs[$items->city_mpg]->name ?? 'Unknown';
                $items->powertrain_type = $powertrain_types[$items->powertrain_type]->name ?? 'Unknown';
                
                $decodedItems[] = $items;
            }
        }

        

        return response()->json([
            'status' => 'success',
            'message' => 'Order items retrieved successfully',
            'data' => $decodedItems,
        ], 200);
    }
}
