<!-- resources/views/attachments/upload.blade.php -->

<form action="{{ route('attachments.upload', $todo->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="attachment" required>
    <button type="submit">Upload</button>
</form>
