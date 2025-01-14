<?php

namespace Tests\Unit;

use App\Services\ImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportServiceTest extends TestCase
{
    use RefreshDatabase;

    private ImportService $importService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importService = app(ImportService::class);
    }

    public function test_can_start_import_process()
    {
        $csv = UploadedFile::fake()->createWithContent(
            'test.csv',
            "name,price,unit,quantity\nTest Product,99.99,pcs,100"
        );

        $importId = $this->importService->importProducts($csv);

        $this->assertNotNull($importId);
        $this->assertDatabaseHas('jobs', []);
    }
} 