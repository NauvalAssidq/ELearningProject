<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Enroll the student in a module.
     */
    public function enroll(Module $module)
    {
        $user = Auth::user();

        // Check if already enrolled
        if ($user->isEnrolledIn($module)) {
            return back()->with('info', 'Anda sudah terdaftar di modul ini.');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'enrolled_at' => now(),
        ]);

        return back()->with('success', 'Berhasil mendaftar ke modul "' . $module->title . '"!');
    }

    /**
     * Unenroll the student from a module.
     */
    public function unenroll(Module $module)
    {
        $user = Auth::user();

        $enrollment = $user->enrollments()->where('module_id', $module->id)->first();

        if (!$enrollment) {
            return back()->with('warning', 'Anda tidak terdaftar di modul ini.');
        }

        $enrollment->delete();

        return redirect()->route('student.modules.index')
            ->with('success', 'Anda telah keluar dari modul "' . $module->title . '".');
    }
}
