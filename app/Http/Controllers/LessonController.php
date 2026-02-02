<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function create(Module $module)
    {
        return view('lecturer.lessons.create', compact('module'));
    }

    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_url' => 'nullable|file|mimes:mp4,mov,ogg,jpg,jpeg,png,webp|max:102400', // 100MB limit example
            'duration' => 'nullable|integer',
            'content' => 'nullable|string',
            'is_preview' => 'boolean',
        ]);

        if ($request->hasFile('media_url')) {
            $path = $request->file('media_url')->store('lessons', 'public');
            $validated['media_url'] = 'storage/' . $path;
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['order'] = $module->lessons()->count() + 1;

        $module->lessons()->create($validated);

        return redirect()->route('lecturer.modules.show', $module)->with('success', 'Pelajaran berhasil ditambahkan.');
    }

    public function edit(Module $module, Lesson $lesson)
    {
        return view('lecturer.lessons.edit', compact('module', 'lesson'));
    }

    public function update(Request $request, Module $module, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media_url' => 'nullable|file|mimes:mp4,mov,ogg,jpg,jpeg,png,webp|max:102400',
            'duration' => 'nullable|integer',
            'content' => 'nullable|string',
            'is_preview' => 'boolean',
        ]);

        if ($request->hasFile('media_url')) {
             if ($lesson->media_url && Str::startsWith($lesson->media_url, 'storage/')) {
                $oldPath = str_replace('storage/', '', $lesson->media_url);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('media_url')->store('lessons', 'public');
            $validated['media_url'] = 'storage/' . $path;
        } elseif ($request->has('remove_media') && $request->remove_media == '1') {
             if ($lesson->media_url && Str::startsWith($lesson->media_url, 'storage/')) {
                $oldPath = str_replace('storage/', '', $lesson->media_url);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $validated['media_url'] = null;
        } else {
            // Keep old URL if not uploading or removing
            unset($validated['media_url']);
        }

        if ($lesson->title !== $validated['title']) {
             $validated['slug'] = Str::slug($validated['title']);
        }
        
        $validated['is_preview'] = $request->has('is_preview');

        $lesson->update($validated);

        return redirect()->route('lecturer.modules.show', $module)->with('success', 'Pelajaran berhasil diperbarui.');
    }

    public function destroy(Module $module, Lesson $lesson)
    {
        if ($lesson->media_url && Str::startsWith($lesson->media_url, 'storage/')) {
             $oldPath = str_replace('storage/', '', $lesson->media_url);
             \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
        }

        $lesson->delete();
        return redirect()->route('lecturer.modules.show', $module)->with('success', 'Pelajaran berhasil dihapus.');
    }
    
    public function show(Module $module, Lesson $lesson)
    {
        // Return different views based on user role
        if (auth()->user()->hasRole('student')) {
            // Auto-complete if no quiz
            if (!$lesson->quiz) {
                 \App\Models\LessonProgress::firstOrCreate([
                     'user_id' => auth()->id(),
                     'lesson_id' => $lesson->id,
                 ], [
                     'is_completed' => true, 
                     'completed_at' => now()
                 ]);
            }
            return view('student.lessons.show', compact('module', 'lesson'));
        }
        
        return view('lecturer.lessons.show', compact('module', 'lesson'));
    }
}
