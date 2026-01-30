<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\LevelUpAttempt;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Show the assessment landing page.
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect if already at max level
        if ($user->isMaxLevel()) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah berada di level tertinggi (Mahir).');
        }

        $canTakeAssessment = $user->canTakeLevelUpAssessment();
        $daysRemaining = $user->getDaysUntilNextAttempt();
        $nextLevel = $user->getNextLevel();

        // Get last attempt for display
        $lastAttempt = $user->levelUpAttempts()->latest()->first();

        return view('student.assessment.index', compact(
            'user',
            'canTakeAssessment',
            'daysRemaining',
            'nextLevel',
            'lastAttempt'
        ));
    }

    /**
     * Start the assessment - generate questions and store in session.
     */
    public function start(Request $request)
    {
        $user = auth()->user();

        // Check if can take assessment
        if (!$user->canTakeLevelUpAssessment()) {
            return redirect()->route('assessment.index')
                ->with('error', 'Anda masih dalam masa cooldown. Silakan coba lagi nanti.');
        }

        // Get 15 random questions from BankSoal filtered by current skill level
        $questions = BankSoal::active()
            ->difficulty($user->skill_level)
            ->with('options')
            ->inRandomOrder()
            ->take(15)
            ->get();

        if ($questions->count() < 15) {
            return redirect()->route('assessment.index')
                ->with('error', 'Tidak cukup soal tersedia untuk level Anda. Silakan hubungi dosen.');
        }

        // Store question IDs in session
        session([
            'assessment_questions' => $questions->pluck('id')->toArray(),
            'assessment_started_at' => now(),
        ]);

        return view('student.assessment.quiz', compact('questions', 'user'));
    }

    /**
     * Submit and grade the assessment.
     */
    public function submit(Request $request)
    {
        $user = auth()->user();

        // Validate answers
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer',
        ]);

        // Get questions from session
        $questionIds = session('assessment_questions', []);
        if (empty($questionIds)) {
            return redirect()->route('assessment.index')
                ->with('error', 'Sesi asesmen tidak valid. Silakan mulai ulang.');
        }

        // Calculate score
        $questions = BankSoal::with('options')->whereIn('id', $questionIds)->get();
        $correctCount = 0;

        foreach ($questions as $question) {
            $selectedOptionId = $validated['answers'][$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);
            
            if ($correctOption && $selectedOptionId == $correctOption->id) {
                $correctCount++;
            }
        }

        $score = round(($correctCount / 15) * 100);
        $passed = $score >= 75; // 75% passing grade (12/15)

        // Record attempt
        $attempt = LevelUpAttempt::create([
            'user_id' => $user->id,
            'from_level' => $user->skill_level,
            'to_level' => $user->getNextLevel(),
            'score' => $score,
            'passed' => $passed,
        ]);

        // If passed, upgrade user level
        if ($passed) {
            $user->update(['skill_level' => $user->getNextLevel()]);
        }

        // Clear session
        session()->forget(['assessment_questions', 'assessment_started_at']);

        return view('student.assessment.result', [
            'user' => $user->fresh(),
            'attempt' => $attempt,
            'correctCount' => $correctCount,
            'totalQuestions' => 15,
        ]);
    }
}
