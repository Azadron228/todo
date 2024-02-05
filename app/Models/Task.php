<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'attachment',
        'status'
    ];

    public function scopeFilterStatus($query, $statuses)
    {
        return $query->whereIn('status', $statuses);
    }

    public function scopeSearchText($query, $text)
    {
        return $query->where('text', 'like', '%' . $text . '%');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
