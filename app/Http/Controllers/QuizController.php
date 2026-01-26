<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuizAttempt;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Show form to create a new quiz for a lesson.
     */
    public function create(Module $module)
    {
        $lessons = $module->lessons()->doesntHave('quiz')->get();
        
        return view('lecturer.quizzes.create', compact('module', 'lessons'));
    }

    /**
     * Store a new quiz.
     */
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'required|integer|min:1|max:180',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.correct' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $module) {
            $quiz = Quiz::create([
                'module_id' => $module->id,
                'lesson_id' => $validated['lesson_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'time_limit' => $validated['time_limit'],
            ]);

            foreach ($validated['questions'] as $index => $questionData) {
                $question = $quiz->questions()->create([
                    'question_text' => $questionData['text'],
                ]);

                foreach ($questionData['options'] as $optionIndex => $optionData) {
                    $question->options()->create([
                        'option_text' => $optionData['text'],
                        'is_correct' => $optionIndex == $questionData['correct'],
                    ]);
                }
            }
        });

        return redirect()->route('lecturer.modules.show', $module)
            ->with('success', 'Kuis berhasil dibuat!');
    }

    /**
     * Show form to edit a quiz.
     */
    public function edit(Module $module, Quiz $quiz)
    {
        $quiz->load('questions.options');
        $lesson = $quiz->lesson;
        
        return view('lecturer.quizzes.edit', compact('module', 'quiz', 'lesson'));
    }

    /**
     * Update an existing quiz.
     */
    public function update(Request $request, Module $module, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'required|integer|min:1|max:180',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.correct' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $quiz) {
            $quiz->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'time_limit' => $validated['time_limit'],
            ]);

            // Delete old questions and options
            $quiz->questions()->each(function ($question) {
                $question->options()->delete();
                $question->delete();
            });

            // Create new questions and options
            foreach ($validated['questions'] as $index => $questionData) {
                $question = $quiz->questions()->create([
                    'question_text' => $questionData['text'],
                ]);

                foreach ($questionData['options'] as $optionIndex => $optionData) {
                    $question->options()->create([
                        'option_text' => $optionData['text'],
                        'is_correct' => $optionIndex == $questionData['correct'],
                    ]);
                }
            }
        });

        return redirect()->route('lecturer.modules.show', $module)
            ->with('success', 'Kuis berhasil diperbarui!');
    }

    /**
     * Delete a quiz.
     */
    public function destroy(Module $module, Quiz $quiz)
    {
        DB::transaction(function () use ($quiz) {
            $quiz->questions()->each(function ($question) {
                $question->options()->delete();
                $question->delete();
            });
            $quiz->delete();
        });

        return redirect()->route('lecturer.modules.show', $module)
            ->with('success', 'Kuis berhasil dihapus!');
    }

    /**
     * Show quiz start page for students.
     */
    public function start(Module $module, Lesson $lesson)
    {
        $user = Auth::user();
        $quiz = $lesson->quiz;

        if (!$quiz) {
            return back()->with('error', 'Kuis tidak ditemukan untuk pelajaran ini.');
        }

        // Allow retaking the quiz - no redirect even if previously completed
        $questions = $quiz->questions()->with('options')->get();

        return view('student.quizzes.start', compact('module', 'lesson', 'quiz', 'questions'));
    }

    /**
     * Submit quiz and calculate score.
     */
    public function submit(Request $request, Module $module, Lesson $lesson)
    {
        $user = Auth::user();
        $quiz = $lesson->quiz;

        if (!$quiz) {
            return back()->with('error', 'Kuis tidak ditemukan.');
        }

        $answers = $request->input('answers', []);
        $questions = $quiz->questions()->with('options')->get();

        $correct = 0;
        $total = $questions->count();

        foreach ($questions as $question) {
            $selectedOptionId = $answers[$question->id] ?? null;
            if ($selectedOptionId) {
                $option = Option::find($selectedOptionId);
                if ($option && $option->is_correct) {
                    $correct++;
                }
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        $passed = $score >= 60;

        // Create new quiz attempt record for each submission
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'started_at' => now()->subMinutes($quiz->time_limit),
            'completed_at' => now(),
        ]);

        // Update or create lesson progress - always update with latest attempt if passed
        if ($passed) {
            $progress = LessonProgress::updateOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                [
                    'is_completed' => true,
                    'quiz_attempt_id' => $attempt->id,
                    'completed_at' => now(),
                ]
            );
        }

        return view('student.quizzes.result', compact('module', 'lesson', 'quiz', 'score', 'correct', 'total', 'passed', 'attempt'));
    }
}
