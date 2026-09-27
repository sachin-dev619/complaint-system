<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;

    /** @var array<int, array{row: int, errors: array<int, string>}> */
    public array $failures = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $excelRow = $index + 2; // heading row is 1

            $data = [
                'first_name' => trim((string) ($row['first_name'] ?? '')),
                'last_name' => trim((string) ($row['last_name'] ?? '')),
                'email' => trim((string) ($row['email'] ?? '')),
                'roll_no' => trim((string) ($row['roll_no'] ?? '')),
                'class_models_id' => is_numeric($row['class_models_id'] ?? null)
                    ? (int) $row['class_models_id']
                    : null,
                'division_id' => is_numeric($row['division_id'] ?? null)
                    ? (int) $row['division_id']
                    : null,
                'gender' => trim((string) ($row['gender'] ?? '')),
                'phone' => preg_replace('/\D+/', '', (string) ($row['phone'] ?? '')),
            ];

            // Skip completely empty rows
            if (
                $data['first_name'] === ''
                && $data['last_name'] === ''
                && $data['email'] === ''
                && $data['roll_no'] === ''
            ) {
                continue;
            }

            $validator = Validator::make($data, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'roll_no' => 'required|string|max:50',
                'class_models_id' => 'required|exists:class_models,id',
                'division_id' => 'required|exists:divisions,id',
                'gender' => 'required|in:Male,Female',
                'phone' => 'required|regex:/^[0-9]{10}$/',
            ], [
                'first_name.required' => 'First name is required',
                'last_name.required' => 'Last name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Enter a valid email',
                'email.unique' => 'Email already exists',
                'roll_no.required' => 'Roll number is required',
                'class_models_id.required' => 'Class ID is required',
                'class_models_id.exists' => 'Class ID is invalid',
                'division_id.required' => 'Division ID is required',
                'division_id.exists' => 'Division ID is invalid',
                'gender.required' => 'Gender is required',
                'gender.in' => 'Gender must be Male or Female',
                'phone.required' => 'Phone is required',
                'phone.regex' => 'Phone must be a 10-digit number',
            ]);

            if ($validator->fails()) {
                $this->failures[] = [
                    'row' => $excelRow,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            $duplicateRoll = Student::where('roll_no', $data['roll_no'])
                ->where('class_models_id', $data['class_models_id'])
                ->where('division_id', $data['division_id'])
                ->exists();

            if ($duplicateRoll) {
                $this->failures[] = [
                    'row' => $excelRow,
                    'errors' => ['Roll number already exists for this class and division'],
                ];
                continue;
            }

            Student::create($data);
            $this->imported++;
        }
    }
}
