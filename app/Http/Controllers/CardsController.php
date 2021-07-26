<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\Columns;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function index()
    {
        $columns = Columns::with('cards')->get();

        return view('cards.index', compact('columns'));
    }

    //For new cards added
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string'],
            'description' => ['string'],
            'columns_id' => ['required', 'exists:columns,id']
        ]);

        $lastState = Cards::where('columns_id', $request->input('columns_id'))->max('state');

        $state = $lastState + 1;

        $card = new Cards;

        $card->title = $request->input('title');
        $card->description = $request->input('description');
        $card->state = $state;
        $card->columns_id = $request->input('columns_id');

        return Cards::create(['title' => $card->title, 'description' => $card->description, 'state' => $card->state, 'columns_id' => $card->columns_id]);

    }

    //Update cards location
    public function sync(Request $request)
    {
        foreach ($request->columns as $column) {
            foreach ($column['cards'] as $i => $card) {
                $state = $i + 1;

                if ($card['columns_id'] !== $column['id'] || $card['state'] !== $state) {
                        $newCard = Cards::find($card['id']);

                        $newCard->columns_id = $column['id'];
                        $newCard->state = $state;

                        $newCard->save();
                }
            }
        }
    }
}