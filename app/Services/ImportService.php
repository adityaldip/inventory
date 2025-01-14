<?php

namespace App\Services;

use App\Jobs\ImportProductsJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportService
{
    private const CHUNK_SIZE = 1000;

    public function importProducts(UploadedFile $file): string
    {
        try {
            // Create a unique import ID
            $importId = 'import_' . uniqid();
            
            // Read CSV
            $csv = Reader::createFromPath($file->getRealPath());
            $csv->setHeaderOffset(0);
            
            // Get total records
            $totalRecords = count($csv);
            $totalChunks = ceil($totalRecords / self::CHUNK_SIZE);
            
            // Initialize progress in cache
            Cache::put($importId . '_total', $totalChunks, now()->addHours(24));
            Cache::put($importId . '_progress', 0, now()->addHours(24));
            
            // Process in chunks
            $records = Statement::create()->process($csv);
            $chunks = array_chunk(iterator_to_array($records), self::CHUNK_SIZE);
            
            foreach ($chunks as $index => $chunk) {
                ImportProductsJob::dispatch($chunk, $index)
                    ->onQueue('imports')
                    ->delay(now()->addSeconds($index * 2)); // Spread out the jobs
            }
            
            return $importId;
            
        } catch (\Exception $e) {
            logger()->error('Import initialization failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getImportProgress(string $importId): array
    {
        $total = Cache::get($importId . '_total', 0);
        $progress = Cache::get($importId . '_progress', 0);
        
        $percentage = $total > 0 ? ($progress / $total) * 100 : 0;
        
        return [
            'total' => $total,
            'processed' => $progress,
            'percentage' => round($percentage, 2),
            'completed' => $progress >= $total
        ];
    }
} 