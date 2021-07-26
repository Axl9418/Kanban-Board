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

    public function destroy(Request $request)
    {
        $column = new Columns();

        $column->id = $request->id;

        $column_delete = Columns::find($column->id);

        //Delete cards of this column
        Cards::where('columns_id', '=' , $column_delete->id)->delete();
        
        //delete column
        return $column_delete->delete();


    }
}
