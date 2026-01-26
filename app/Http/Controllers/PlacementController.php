<?php

namespace App\Http\Controllers;

use App\Models\PlacementQuiz;
use App\Models\BankSoal;
use App\Models\BankSoalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlacementController extends Controller
{
    /**
     * Show placement quiz introduction page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // If already completed, redirect to dashboard
        if ($user->hasCompletedPlacement()) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah menyelesaikan tes penempatan.');
        }

        // Check if there are enough questions in Bank Soal
        $questionCount = BankSoal::active()->count();

        if ($questionCount < 1) {
            // No questions available, assign default level
            $user->update([
                'skill_level' => 'pemula',
                'placement_completed_at' => now(),
            ]);
            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang! Anda telah ditempatkan sebagai Pemula.');
        }

        return view('placement.index', [
            'questionCount' => min($questionCount, 10),
            'timeLimit' => 15, // Fixed 15 minutes for placement
        ]);
    }

    /**
     * Show placement quiz questions.
     */
    public function start()
    {
        $user = Auth::user();
        
        if ($user->hasCompletedPlacement()) {
            return redirect()->route('dashboard');
        }

        // Get 10 random questions from Bank Soal
        $questions = BankSoal::getRandomForPlacement(10);
        
        if ($questions->isEmpty()) {
            return redirect()->route('placement.index')
                ->with('error', 'Tidak ada pertanyaan tersedia.');
        }

        // Store question IDs in session for validation during submit
        session(['placement_question_ids' => $questions->pluck('id')->toArray()]);

        return view('placement.quiz', [
            'questions' => $questions,
            'timeLimit' => 15,
        ]);
    }

    /**
     * Submit placement quiz and calculate score.
     */
    public function submit(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasCompletedPlacement()) {
            return redirect()->route('dashboard');
        }

        // Get the question IDs from session
        $questionIds = session('placement_question_ids', []);
        
        if (empty($questionIds)) {
            return redirect()->route('placement.index')
                ->with('error', 'Sesi kuis tidak valid. Silakan mulai ulang.');
        }

        $answers = $request->input('answers', []);
        $questions = BankSoal::whereIn('id', $questionIds)->with('options')->get();
        
        $correct = 0;
        $total = $questions->count();

        foreach ($questions as $question) {
            $selectedOptionId = $answers[$question->id] ?? null;
            
            if ($selectedOptionId) {
                $option = BankSoalOption::find($selectedOptionId);
                if ($option && $option->is_correct) {
                    $correct++;
                }
            }
        }

        // Calculate percentage
        $percentage = $total > 0 ? ($correct / $total) * 100 : 0;

        // Determine skill level based on score
        $skillLevel = match(true) {
            $percentage >= 71 => 'mahir',
            $percentage >= 41 => 'menengah',
            default => 'pemula',
        };

        // Update user
        $user->update([
            'skill_level' => $skillLevel,
            'placement_completed_at' => now(),
        ]);

        // Clear session
        session()->forget('placement_question_ids');

        // Store result in session for result page
        session(['placement_result' => [
            'correct' => $correct,
            'total' => $total,
            'percentage' => round($percentage, 1),
            'skill_level' => $skillLevel,
        ]]);

        return redirect()->route('placement.result');
    }

    /**
     * Show placement result.
     */
    public function result()
    {
        $user = Auth::user();
        
        if (!$user->hasCompletedPlacement()) {
            return redirect()->route('placement.index');
        }

        $result = session('placement_result', [
            'correct' => 0,
            'total' => 0,
            'percentage' => 0,
            'skill_level' => $user->skill_level,
        ]);

        return view('placement.result', compact('result', 'user'));
    }
}
