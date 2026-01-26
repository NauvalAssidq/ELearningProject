<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\BankSoalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankSoalController extends Controller
{
    /**
     * Display a listing of questions in the bank.
     */
    public function index(Request $request)
    {
        $query = BankSoal::with(['creator', 'options'])
            ->latest();

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by own questions only
        if ($request->filled('mine') && $request->mine === '1') {
            $query->where('created_by', Auth::id());
        }

        $questions = $query->paginate(15);

        return view('lecturer.bank-soal.index', compact('questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        return view('lecturer.bank-soal.create');
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|min:10',
            'difficulty' => 'required|in:easy,medium,hard',
            'options' => 'required|array|min:2|max:6',
            'options.*.text' => 'required|string|min:1',
            'correct' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $question = BankSoal::create([
                'question_text' => $validated['question_text'],
                'difficulty' => $validated['difficulty'],
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);

            foreach ($validated['options'] as $index => $option) {
                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $index == $validated['correct'],
                ]);
            }
        });

        return redirect()->route('lecturer.bank-soal.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan ke Bank Soal.');
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(BankSoal $bankSoal)
    {
        $bankSoal->load('options');
        
        return view('lecturer.bank-soal.edit', compact('bankSoal'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, BankSoal $bankSoal)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|min:10',
            'difficulty' => 'required|in:easy,medium,hard',
            'options' => 'required|array|min:2|max:6',
            'options.*.text' => 'required|string|min:1',
            'correct' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated, $request, $bankSoal) {
            $bankSoal->update([
                'question_text' => $validated['question_text'],
                'difficulty' => $validated['difficulty'],
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Delete old options and create new ones
            $bankSoal->options()->delete();
            
            foreach ($validated['options'] as $index => $option) {
                $bankSoal->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $index == $validated['correct'],
                ]);
            }
        });

        return redirect()->route('lecturer.bank-soal.index')
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(BankSoal $bankSoal)
    {
        $bankSoal->delete();

        return redirect()->route('lecturer.bank-soal.index')
            ->with('success', 'Pertanyaan berhasil dihapus dari Bank Soal.');
    }

    /**
     * Toggle active status of a question.
     */
    public function toggleActive(BankSoal $bankSoal)
    {
        $bankSoal->update([
            'is_active' => !$bankSoal->is_active,
        ]);

        $status = $bankSoal->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
            ->with('success', "Pertanyaan berhasil {$status}.");
    }
}
