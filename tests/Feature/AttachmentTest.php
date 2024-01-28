<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    public function test_file_upload()
    {
        Storage::fake('attachments');
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);
        Auth::login($user);

        $file = UploadedFile::fake()->image('test_image.jpg');

        $this->post("/task/{$task->id}/attachments", [
            'attachment' => $file,
        ]);

        Storage::disk('public')->assertExists($file->hashName());
    }
}
