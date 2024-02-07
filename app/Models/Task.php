<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'attachment',
        'status',
        'priority'
    ];

    protected $attributes = [
        'attachment' => '',
        'status' => 'in_progress',
        'priority' => '1',
    ];

    public function scopeOfStatus(Builder $query, array $statuses): void
    {
        $query->whereIn('status', $statuses);
    }

    public function scopeOfText(Builder $query, string $text): void
    {
        $query->where('text', 'like', '%' . $text . '%');
    }

    public function scopeOfPriority(Builder $query, array $priorities): void
    {
        $query->whereIn('priority', $priorities);
    }

    public function scopeFilterAndOrder($query, $request)
    {
        $priorityOrder = $request->input('priority_order', 'asc');
        $dateOrder = $request->input('date_order', 'asc');

        if ($request->has('statuses')) {
            $query->whereIn('status', $request->statuses);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('priority')) {
            $priorityOrder = $request->input('priority') == 'asc' ? 'asc' : 'desc';
        }

        if ($request->has('date')) {
            $dateOrder = $request->input('date') == 'asc' ? 'asc' : 'desc';
        }

        return $query->where('user_id', $request->user()->id)
            ->orderBy('priority', $priorityOrder)
            ->orderBy('updated_at', $dateOrder);
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
