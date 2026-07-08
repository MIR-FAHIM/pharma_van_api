<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image1',
        'image2',
        'is_active',
        'status',
        'note',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function imageOne()
    {
        return $this->belongsTo(Upload::class, 'image1');
    }

    public function imageTwo()
    {
        return $this->belongsTo(Upload::class, 'image2');
    }
}
