<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsImportTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'roll_no',
            'class_models_id',
            'division_id',
            'gender',
            'phone',
        ];
    }

    public function array(): array
    {
        // Sample row — replace with real student data before importing
        return [
            [
                'John',
                'Doe',
                'john.doe@example.com',
                '101',
                '1',
                '1',
                'Male',
                '9876543210',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
