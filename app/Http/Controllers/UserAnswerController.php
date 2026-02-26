<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserAnswerRequest;
use App\Http\Resources\UserSessionCollection;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserAnswerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userSessionId = $request['user_session_id'] ?? null;
            if (!$userSessionId) {
                return new UserSessionCollection(false, 'User session ID is required.', []);
            }
            $userSession = UserSession::with('userAnswers')->find($userSessionId);
            if (!$userSession) {
                return new UserSessionCollection(false, 'User session not found.', []);
            }
            return new UserSessionCollection(true, 'User answers retrieved successfully', $userSession);
        } catch (\Throwable $th) {
            return new UserSessionCollection(false, 'Terjadi kesalahan saat mengambil data.', []);
        }
    }

    public function store(StoreUserAnswerRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $answers = $validated['answers'];

            $userSession = UserSession::create([
                'user_id' => Auth::id(),
                'score' => 0,
            ]);
            $questionIds = collect($answers)->pluck('question_id');
            $questions = Question::whereIn('id', $questionIds)
                ->pluck('answer', 'id');

            $insertData = [];
            $score = 0;

            foreach ($answers as $answerData) {

                $correctAnswer = $questions[$answerData['question_id']] ?? null;

                if (!$correctAnswer) {
                    throw new \Exception('Question not found');
                }

                $isCorrect = strtolower($answerData['answer']) ===
                    strtolower($correctAnswer);

                if ($isCorrect) $score++;

                $insertData[] = [
                    'user_session_id' => $userSession->id,
                    'question_id' => $answerData['question_id'],
                    'answer' => $answerData['answer'],
                    'is_correct' => $isCorrect,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            UserAnswer::insert($insertData);
            $userSession->update(['score' => $score]);

            DB::commit();
            return new UserSessionCollection(true, 'User answers stored successfully', $userSession);
        } catch (\Throwable $th) {
            DB::rollBack();
            return new UserSessionCollection(false, $th->getMessage(), []);
        }
    }

    public function show(UserAnswer $userAnswer)
    {
        try {
            return new UserSessionCollection(true, 'User answer retrieved successfully', $userAnswer);
        } catch (\Throwable $th) {
            return new UserSessionCollection(false, 'Failed to retrieve user answer', []);
        }
    }

    public function history()
    {
        try {
            $userSessions = UserSession::where('user_id', Auth::id())->with('userAnswers')->cursorPaginate(10);
            return new UserSessionCollection(true, 'User session history retrieved successfully', $userSessions);
        } catch (\Throwable $th) {
            return new UserSessionCollection(false, 'Failed to retrieve user session history', []);
        }
    }
}
