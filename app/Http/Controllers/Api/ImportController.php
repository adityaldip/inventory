<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct(private ImportService $importService)
    {
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:51200' // 50MB max
        ]);

        try {
            $importId = $this->importService->importProducts($request->file('file'));
            
            return response()->json([
                'message' => 'Import started successfully',
                'import_id' => $importId
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function progress(string $importId)
    {
        return response()->json(
            $this->importService->getImportProgress($importId)
        );
    }
} 