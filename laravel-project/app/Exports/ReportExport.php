<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function collection(): Collection
    {
        return $this->data;
    }
    
    public function headings(): array
    {
        return [
            'Date',
            'Platform',
            'Campaign',
            'Impressions',
            'Clicks',
            'Conversions',
            'Spend',
            'CTR (%)',
        ];
    }
}
