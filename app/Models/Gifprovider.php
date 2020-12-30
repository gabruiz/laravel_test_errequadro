<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gifprovider extends Model
{
    use HasFactory;

    protected $table = "gifproviders";

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'gifprovider_keywords');
    }
}
