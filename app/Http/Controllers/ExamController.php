<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\ExamRequest;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\ExamResource;
use App\Http\Resources\QuestionResource;
use App\Models\Choice;
use App\Models\Exam;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function store(ExamRequest $request)
    {
        try {
            $exam = Exam::create($request->validated());

            return JsonResponse::respondSuccess(
                new ExamResource($exam),
                'Exam has been added successfully'
            );
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();

            return response()->json([
                'status' => true,
                'message' => 'Question deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function addQuestion(QuestionRequest $request, Exam $exam)
    {
        try {
            $question = $exam->questions()->create([
                'question_text' => $request->question_text,
            ]);

            foreach ($request->choices as $choice) {
                $question->choices()->create($choice);
            }

            return JsonResponse::respondSuccess(
                new QuestionResource($question->load('choices')),
                'Question has been added successfully'
            );
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Exam $exam)
    {
        try {
            $exam->load(['questions.choices']);

            return JsonResponse::respondSuccess(
                new ExamResource($exam),
                'Exam fetched successfully'
            );
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function submit(Request $request, Exam $exam)
    {
        try {
            $data = $request->validate([
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|exists:questions,id',
                'answers.*.choice_id'   => 'required|exists:choices,id',
            ]);

            $student = Auth::guard('students')->user();

            $studentExam = StudentExam::create([
                'exam_id'    => $exam->id,
                'student_id' => $student->id,
                'score'      => 0,
            ]);

            $score = 0;
            foreach ($data['answers'] as $answer) {
                $choice = Choice::find($answer['choice_id']);
                $isCorrect = $choice->is_correct;

                StudentAnswer::create([
                    'student_exam_id' => $studentExam->id,
                    'question_id'     => $answer['question_id'],
                    'choice_id'       => $answer['choice_id'],
                    'is_correct'      => $isCorrect,
                ]);

                if ($isCorrect) {
                    $score++;
                }
            }

            $studentExam->update(['score' => $score]);

            return JsonResponse::respondSuccess(
                ['score' => $score],
                'Exam submitted successfully'
            );
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

}
