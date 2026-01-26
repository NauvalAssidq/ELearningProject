<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ProjectSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectSubmissionController extends Controller
{
    public function create(Module $module)
    {
        // INTEGRITY CHECK: Ensure student has passed all quizzes
        if (!$module->hasPassedAllQuizzes(auth()->user())) {
             return redirect()->route('student.modules.show', $module)
                ->with('error', 'Anda harus lulus semua kuis di modul ini sebelum mengerjakan proyek akhir.');
        }

        $existingSubmission = ProjectSubmission::where('module_id', $module->id)
            ->where('user_id', auth()->id())
            ->first();

        return view('student.projects.create', compact('module', 'existingSubmission'));
    }

    public function store(Request $request, Module $module)
    {
        // INTEGRITY CHECK: Prevent backend bypass
        if (!$module->hasPassedAllQuizzes(auth()->user())) {
             abort(403, 'Anda belum menyelesaikan semua kuis.');
        }

        $blockedExtensions = ['php', 'exe', 'bat', 'sh', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'app', 'deb', 'pkg'];
        
        $request->validate([
            'project_file' => [
                'required',
                'file',
                'max:51200', // 50MB in kilobytes
                function ($attribute, $value, $fail) use ($blockedExtensions) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (in_array($extension, $blockedExtensions)) {
                        $fail('File type not allowed for security reasons.');
                    }
                },
            ],
        ]);

        // Delete existing submission if any
        $existing = ProjectSubmission::where('module_id', $module->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            // Delete old file
            if (Storage::disk('public')->exists($existing->file_path)) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->delete();
        }

        $file = $request->file('project_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('projects/' . $module->id, $filename, 'public');

        ProjectSubmission::create([
            'module_id' => $module->id,
            'user_id' => auth()->id(),
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('student.modules.show', $module)
            ->with('success', 'Proyek berhasil disubmit!');
    }

    public function show(Module $module, ProjectSubmission $submission)
    {
        // Ensure user can only view their own submission
        if ($submission->user_id !== auth()->id()) {
            abort(403);
        }

        return view('student.projects.show', compact('module', 'submission'));
    }

    /**
     * List all submissions for a module (Lecturer)
     */
    public function index(Module $module)
    {
        $submissions = ProjectSubmission::where('module_id', $module->id)
            ->with('user')
            ->latest('submitted_at')
            ->get();

        $gradedCount = $submissions->whereNotNull('grade')->count();
        $pendingCount = $submissions->whereNull('grade')->count();

        return view('lecturer.submissions.index', compact('module', 'submissions', 'gradedCount', 'pendingCount'));
    }

    /**
     * Review a single submission (Lecturer)
     */
    public function review(Module $module, ProjectSubmission $submission)
    {
        $submission->load('user');
        
        return view('lecturer.submissions.review', compact('module', 'submission'));
    }

    /**
     * Grade a submission (Lecturer)
     */
    public function grade(Request $request, Module $module, ProjectSubmission $submission)
    {
        $validated = $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'],
        ]);

        return redirect()->route('lecturer.submissions.index', $module)
            ->with('success', 'Nilai berhasil disimpan untuk ' . $submission->user->name);
    }
}
