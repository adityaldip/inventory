<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour
    public $tries = 3;

    public function __construct(private array $chunk, private int $chunkIndex)
    {
    }

    public function handle(): void
    {
        $defaultUser = User::first();

        DB::beginTransaction();
        try {
            foreach ($this->chunk as $row) {
                Product::create([
                    'id' => Str::uuid(),
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'unit' => $row['unit'],
                    'quantity' => $row['quantity'],
                    'created_by_id' => $defaultUser->id
                ]);
            }
            DB::commit();

            // Update progress in cache
            cache()->increment('import_progress');
            
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Import failed for chunk ' . $this->chunkIndex, [
                'error' => $e->getMessage(),
                'chunk' => $this->chunk
            ]);
            throw $e;
        }
    }
} 