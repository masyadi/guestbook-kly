<?php
namespace App\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TableImport implements WithHeadingRow, ToCollection, WithBatchInserts, WithChunkReading
{
    protected $callback;

    public function __construct(callable $callback=null)
    {
        $this->callback = $callback;
    }

    public function collection(Collection $rows)
    {
        $callback = $this->callback;
        
        return $callback($rows);
    }
    
    public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
}
