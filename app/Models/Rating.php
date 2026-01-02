<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'library_service_id',
        'rating',
        'review',
    ];

    // Link to the User who wrote it
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Link to the Service being reviewed
    public function service()
    {
        // We specify 'library_service_id' because the method name 'service' 
        // doesn't match the table name 'library_services' exactly.
        return $this->belongsTo(LibraryService::class, 'library_service_id');
    }
}
