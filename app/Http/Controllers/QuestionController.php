<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionImportExcelRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionCollection;
use App\Imports\QuestionsImport;
use App\Models\Question;
use App\Models\UserInformation;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    public function index()
    {
        try {
            $userInformation = UserInformation::query()->where('user_id', Auth::id())->first();
            $grade_id = $userInformation['grade_id'];
            $difficulty_id = $userInformation['difficulty_id'];

            $questions = Question::query()
                ->where('grade_id', $grade_id)
                ->where('difficulty_id', $difficulty_id)
                ->inRandomOrder()
                ->cursorPaginate(10);

            return new QuestionCollection(true, 'Questions retrieved successfully', $questions);
        } catch (\Throwable $th) {
            return new QuestionCollection(false, 'Failed to fetch questions', []);
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
