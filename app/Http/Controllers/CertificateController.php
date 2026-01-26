<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function show(Module $module)
    {
        $user = auth()->user();

        if (!$module->isCompletedBy($user)) {
            return redirect()->route('student.modules.show', $module)
                ->with('error', 'Anda harus menyelesaikan semua pelajaran dan lulus proyek akhir untuk mendapatkan sertifikat.');
        }

        // Get the graded project submission
        $submission = $module->projectSubmissions()
            ->where('user_id', $user->id)
            ->whereNotNull('grade')
            ->latest()
            ->first();
        
        $grade = $submission ? $submission->grade : null;

        return view('student.certificates.show', compact('module', 'user', 'grade'));
    }
}
