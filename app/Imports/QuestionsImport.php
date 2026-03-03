<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            '*.material_id'   => 'required|exists:materials,id',
            '*.difficulty_id' => 'required|exists:difficulties,id',
            '*.question'      => 'required|string',
            '*.answer'        => 'required|nullable',
        ];
    }

    public function collection($rows)
    {
        try {
            DB::beginTransaction();
            $materialIds = $rows->pluck('material_id')->unique();

            $materials = Material::whereIn('id', $materialIds)
                ->pluck('grade_id', 'id');  

            $insertData = [];

            foreach ($rows as $row) {
                $gradeId = $materials[$row['material_id']] ?? null;
                $insertData[] = [
                    'grade_id'      => $gradeId,
                    'material_id'   => $row['material_id'],
                    'difficulty_id' => $row['difficulty_id'],
                    'content'       => (string) $row['question'],
                    'image_url'     => isset($row['image']) ? '/storage/question-images/' . $row['image'] : null,
                    'answer'        => (string) $row['answer'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
            Question::insert($insertData);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
