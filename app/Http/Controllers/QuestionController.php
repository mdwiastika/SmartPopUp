<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionImportExcelRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionCollection;
use App\Imports\QuestionsImport;
use App\Models\Material;
use App\Models\Question;
use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userInformation = UserInformation::query()->where('user_id', Auth::id())->firstOrFail();
            $grade_id = $userInformation['grade_id'];
            $difficulty_id = $userInformation['difficulty_id'];

            $questions = Question::query()
                ->where('grade_id', $grade_id)
                ->where('difficulty_id', $difficulty_id);

            if ($request->has('material_id')) {
                $material_ids = $request->query('material_id');

                if (is_string($material_ids)) {
                    $material_ids = json_decode($material_ids, true);
                }

                if (is_array($material_ids)) {
                    $questions->whereIn('material_id', $material_ids);
                }
            }
            $questions = $questions->inRandomOrder()
                ->cursorPaginate(10);;


            return new QuestionCollection(true, 'Questions retrieved successfully', $questions);
        } catch (\Throwable $th) {
            return new QuestionCollection(false, 'Failed to fetch questions', []);
        }
    }

    public function indexViaAGeminiAI()
    {
        $userInformation = UserInformation::query()->where('user_id', Auth::id())->with(['grade', 'difficulty'])->firstOrFail();
        $grade = $userInformation->grade->name;
        $difficulty = $userInformation->difficulty->name;
        $materials = Material::query()
            ->where('grade_id', $userInformation->grade_id)
            ->get(['id', 'name']);

        $materialList = $materials
            ->map(fn($m) => "{$m->id}: {$m->name}")
            ->join("\n");

        $prompt = [
            "contents" => [[
                "parts" => [[
                    "text" => "
                Kamu adalah pembuat soal matematika untuk anak SD.

                Tugasmu membuat 10 soal matematika berbentuk ESSAY berdasarkan:
                - Tingkat Kelas: {$grade}
                - Tingkat Kesulitan: {$difficulty}
                - Materi yang tersedia (gunakan id yang tersedia saja):

                {$materialList}

                🔹 ATURAN FORMAT JAWABAN (WAJIB DIPATUHI):

                1. Jawaban HARUS berupa angka saja.
                2. Tidak boleh ada huruf, satuan, atau kata tambahan.
                3. Jika bilangan ribuan atau lebih, jangan gunakan tanda koma sebagai pemisah ribuan.
                Contoh: 1250  |  10000
                4. Jika jawaban berupa waktu, gunakan format HH:MM (24 jam).
                Contoh: 09:30  |  14:05
                5. Jangan gunakan kata seperti 'cm', 'buah', 'bagian', dll.
                6. Jangan gunakan teks seperti 'lebih banyak'.
                7. Hanya gunakan koma jika angka tersebut adalah desimal.
                Contoh: 3,5  |  0,75

                🔹 Aturan Soal:
                - Soal harus sesuai tingkat kelas {$grade}.
                - Soal sesuai tingkat kesulitan {$difficulty}.
                - Hindari soal yang menghasilkan jawaban berupa teks.

                Format output HARUS JSON array dengan 10 item:

                [
                    {
                        \"material_id\": 1,
                        \"question\": \"Teks soal...\",
                        \"answer\": \"123\"
                    }
                ]

                Jangan tambahkan teks lain di luar JSON.
                "
                ]]
            ]],
            "generationConfig" => [
                "responseMimeType" => "application/json",
                "responseSchema" => [
                    "type" => "array",
                    "items" => [
                        "type" => "object",
                        "properties" => [
                            "question" => ["type" => "string"],
                            "answer" => ["type" => "string"],
                            "material_id" => ["type" => "integer"],
                        ],
                        "required" => ["question", "answer", "material_id"]
                    ],
                    "minItems" => 10,
                    "maxItems" => 10
                ]
            ]
        ];
        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=" . env('GEMINI_API_KEY'),
            $prompt
        );

        $data = $response->json();
        $questionContent = $data['candidates'][0]['content'] ?? null;


        if (!$questionContent || !isset($questionContent['parts'][0]['text'])) {
            return new QuestionCollection(false, 'Invalid AI response structure', []);
        }

        $questionRaws = json_decode($questionContent['parts'][0]['text'], true);
        if ($questionRaws && is_array($questionRaws)) {
            $questions = [];
            foreach ($questionRaws as $questionRaw) {
                $questions[] = [
                    'grade_id' => $userInformation['grade_id'],
                    'material_id' => $questionRaw['material_id'],
                    'difficulty_id' => $userInformation['difficulty_id'],
                    'content' => $questionRaw['question'],
                    'answer' => $questionRaw['answer'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $questions = Question::insert($questions);

            $questionInserted = Question::query()
                ->where('grade_id', $userInformation['grade_id'])
                ->where('difficulty_id', $userInformation['difficulty_id'])
                ->whereIn('material_id', $materials->pluck('id'))
                ->latest()
                ->take(10)
                ->get();

            return new QuestionCollection(true, 'Questions retrieved successfully', $questionInserted);
        } else {
            return new QuestionCollection(false, 'Failed to fetch questions from Gemini AI', []);
        }
    }

    public function store(StoreQuestionRequest $request)
    {
        try {
            $validated = $request->validated();
            $question = Question::create($validated);
            return new QuestionCollection(true, 'Question created successfully', $question);
        } catch (\Throwable $th) {
            return new QuestionCollection(false, 'Failed to create question', []);
        }
    }

    public function show(Question $question)
    {
        try {
            return new QuestionCollection(true, 'Question retrieved successfully', $question);
        } catch (\Throwable $th) {
            return new QuestionCollection(false, 'Failed to retrieve question', []);
        }
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        try {
            $validated = $request->validated();
            $question->update($validated);
            return new QuestionCollection(true, 'Question updated successfully', $question);
        } catch (\Throwable $th) {
            return new QuestionCollection(false, 'Failed to update question', []);
        }
    }

    public function destroy(Question $question)
    {
        try {
            $question->delete();
            return new QuestionCollection(true, 'Question deleted successfully', []);
        } catch (\Throwable $th) {
            return new QuestionCollection(false, 'Failed to delete question', []);
        }
    }

    public function importFromExcel(QuestionImportExcelRequest $request)
    {
        try {
            Excel::import(new QuestionsImport, $request->file('file'));
            return new QuestionCollection(true, 'Questions imported successfully', []);
        } catch (\Throwable $th) {
            return new QuestionCollection(false, $th->getMessage(), []);
        }
    }
}
