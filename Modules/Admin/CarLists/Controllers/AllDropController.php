<?php

namespace Modules\Admin\CarLists\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Modules\Admin\Body_Type\Models\Body_Type;
use Modules\Admin\CarModel\Models\Carmodel;
use Modules\Admin\Category\Models\Category;
use Modules\Admin\Color\Models\Color;
use Modules\Admin\Condition\Models\Condition;
use Modules\Admin\Cylinders\Models\Cylinder;
use Modules\Admin\Doors\Models\Door;
use Modules\Admin\Drive_Type\Models\Drive_type;
use Modules\Admin\Fuel_Type\Models\Fuel_type;
use Modules\Admin\Make\Models\Make;
use Modules\Admin\Transmission\Models\Transmission;

class AllDropController extends Controller
{
    public function index(){
        $make = Make::all();
        $model = Carmodel::all();
        $body_type = Body_Type::all();
        $drive_type = Drive_type::all();
        $transmission = Transmission::all();
        $condition = Condition::all();
        $fuel_type = Fuel_type::all();
        $door = Door::all();
        $cylinder = Cylinder::all();
        $color = Color::all();
        $category = Category::all();
        return response([
            'make'=>$make,
            'model'=>$model,
            'body_type'=>$body_type,
            'drive_type'=>$drive_type,
            'transmission'=>$transmission,
            'condition'=>$condition,
            'fuel_type'=>$fuel_type,
            'door'=>$door,
            'cylinder'=>$cylinder,
            'color'=>$color,
            'category'=>$category,
        ],200);
    }
}
