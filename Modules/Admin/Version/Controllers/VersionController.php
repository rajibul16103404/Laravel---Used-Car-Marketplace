<?php

namespace Modules\Admin\Version\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Version\Models\Version;

class VersionController extends Controller
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

        $version = Version::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Version Added Successfully',
            'data' => $version,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $version = version::all();

        // return response()->json([
        //     'message' => 'version data retrieved',
        //     'data' => $version,
        // ], 200);

        if($request->page === '0'){
            $perPage =  Version::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = Version::paginate($perPage);

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
        $version = Version::find($id);

        // Check if product exists
        if (!$version) {
            return response()->json([
                'message' => 'version not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Version data retrieved successfully',
            'data' => $version,
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

        // Find the Version record
        $version = Version::find($id);

        if (!$version) {
            return response()->json(['message' => 'Version Not Found'], 404);
        }

        // Update the record
        $version->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Version Updated Successfully',
            'data' => $version,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Version record
        $version = Version::find($id);

        if (!$version) {
            return response()->json(['message' => 'Version Not Found'], 404);
        }

        // Delete the record
        $version->delete();

        // Return success response
        return response()->json([
            'message' => 'Version Deleted Successfully',
        ], 200);
    }


}
