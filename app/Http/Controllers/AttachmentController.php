<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function upload(Request $request, $todoId)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $todo = Task::find($todoId);

        if ($todo) {
            $attachmentPath = Storage::putFile('public', $request->file('attachment'));

            $todo->attachments()->create([
                'path' => $attachmentPath,
                'original_name' => $request->file('attachment')->getClientOriginalName(),
            ]);

            return response()->json(['Attachment uploaded successfully.']);
        } else {
            return response()->json(['error' => 'Task not found.'], 404);
        }
    }

    public function download($attachmentId)
    {
        $attachment = Attachment::find($attachmentId);

        return Storage::download($attachment->path, $attachment->original_name);
    }
}
