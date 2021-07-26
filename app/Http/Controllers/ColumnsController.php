<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\Columns;
use Illuminate\Http\Request;

class ColumnsController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string'],
            'slug' => ['required', 'string']
        ]);
        
        $column = new Columns();

        $column->title = $request->input('title');
        $column->slug = $request->input('slug');

        return Columns::create(['title' => $column->title, 'slug' => $column->slug]);

    }
}
