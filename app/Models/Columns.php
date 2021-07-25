<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//not sure
use App\Models\Cards;

class Columns extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug'];

    public $timestamps = false;

    public function cards()
    {
        return $this->hasMany(Cards::class)->orderBy('state');
    }

}
