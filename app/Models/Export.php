<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Export extends MainModel
{
    protected $table = 'exports';


     protected $fillable = [
        'completed_at',
        'file_disk',
        'file_name',
        'exporter',
        'processed_rows',
        'total_rows',
        'successful_rows',
        'user_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDownloadUrlAttribute(): ?string
{
    return route('exports.download', $this);
}


}
