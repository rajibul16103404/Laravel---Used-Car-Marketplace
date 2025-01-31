<?php

namespace Modules\Admin\DriveTrain\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\DriveTrain\Models\DriveTrain;

class DriveTrainController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $drivetrain = DriveTrain::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Drive Train Added Successfully',
            'data' => $drivetrain,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $drivetrain = drivetrain::all();

        // return response()->json([
        //     'message' => 'drivetrain data retrieved',
        //     'data' => $drivetrain,
        // ], 200);

        if($request->page === '0'){
            $perPage =  DriveTrain::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = DriveTrain::paginate($perPage);

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

    public function show($id)
    {
        // Find product by ID
        $drivetrain = DriveTrain::find($id);

        // Check if product exists
        if (!$drivetrain) {
            return response()->json([
                'message' => 'Drive Train not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Drive Train data retrieved successfully',
            'data' => $drivetrain,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the Drive Train record
        $drivetrain = DriveTrain::find($id);

        if (!$drivetrain) {
            return response()->json(['message' => 'Drive Train Not Found'], 404);
        }

        // Update the record
        $drivetrain->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Drive Train Updated Successfully',
            'data' => $drivetrain,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Drive Train record
        $drivetrain = DriveTrain::find($id);

        if (!$drivetrain) {
            return response()->json(['message' => 'Drive Train Not Found'], 404);
        }

        // Delete the record
        $drivetrain->delete();

        // Return success response
        return response()->json([
            'message' => 'Drive Train Deleted Successfully',
        ], 200);
    }


}
