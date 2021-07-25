<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//not sure
use App\Models\Columns;

class Cards extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'state', 'columns_id'];

    public function columns()
    {
        return $this->belongsTo(Columns::class);
    }
}
