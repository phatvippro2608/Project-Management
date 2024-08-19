<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardsReorderUpdateRequest;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Fluent;

class CardsReorderUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CardsReorderUpdateRequest $request): RedirectResponse
    {
        $data = collect($request->columns)
            ->map(function ($column) {
                // Convert cards to a collection and map each card to the desired format
                return collect($column['cards'])->map(function ($card) use ($column) {
                    return [
                        'id' => $card['id'],
                        'position' => $card['position'],
                        'column_id' => $column['id']
                    ];
                });
            })
            // Flatten the array to get a single level of cards
            ->flatten(1)
            // Convert the collection to an array
            ->toArray();

// Perform the batch upsert
        Card::query()->upsert($data, ['id'], ['position', 'column_id']);

        return back();
    }
}
