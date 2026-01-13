<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryService extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'avg_rating',
    ];

    // Optional: Cast rating to float so PHP treats "4.50" as 4.5 (number) instead of string
    protected function casts(): array
    {
        return [
            'avg_rating' => 'float',
        ];
    }
    
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
