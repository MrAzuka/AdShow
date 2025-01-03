<?php

namespace App\Models;

use App\Models\Ads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'url',
    ];

    public function ad()
    {
        return $this->belongsTo(Ads::class);
    }
}
