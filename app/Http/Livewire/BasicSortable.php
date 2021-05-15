<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Livewire\Component;
use App\Models\politician;

class BasicSortable extends Component
{
    public $politicians;

    private function fetch_politicians () {
        $this->politicians = DB::table('politicians')
                            ->join('political_positions', 'political_positions.id', '=', 'politicians.political_position_id')
                            ->select('political_positions.position', 'politicians.*')
                            ->orderBy('politicians.political_position_id', 'ASC')->get();
    }

    public function updatePoliticalPosition ($list) {
        foreach($list as $item) {
            politician::find($item['value'])->update(['political_position_id' => $item['order']]);
        }
    }

    public function render()
    {
        $this->fetch_politicians ();
        return view('livewire.basic-sortable');
    }
}
