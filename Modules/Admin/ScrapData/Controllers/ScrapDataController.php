<?php

namespace Modules\Admin\ScrapData\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\ScrapData\Models\Scraping_log;

class ScrapDataController extends Controller
{

    public function index(Request $request)
    {
        try {
            if ($request->page === '0') {
                $perPage = Scraping_log::count();
            } else {
                $perPage = $request->input('per_page', 10);
            }
    
            $data = Scraping_log::paginate($perPage);
    
            return response()->json([
                'pagination' => [
                    'total_count' => $data->total(),
                    'total_page' => $data->lastPage(),
                    'current_page' => $data->currentPage(),
                    'current_page_count' => $data->count(),
                    'next_page' => $data->hasMorePages() ? $data->currentPage() + 1 : null,
                    'previous_page' => $data->onFirstPage() ? null : $data->currentPage()
                ],
                'message' => 'Data Retrieved Successfully',
                'data' => $data->items(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    
}
