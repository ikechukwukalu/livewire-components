<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;

    public $columns;
    public $page_options;
    public $fetch;
    public $order_by;
    public $search;
    public $sort;
    public $maxP;
    public $total;
    public $set;
    public $current_page;
    public $load_state = 'Initializing table...';

    protected $queryString = ['search', 'fetch'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteUser' => 'delete_user', 'reloadDatatable' => 'make_datatable'];

    private $users = [];


    /**
     * Private Functions
    */
    private function export_data_from_table($type = 'pdf', $json = false) {
        $body = [];
        $total = User::count();
        $users = $total > $this->maxP ? $this->implement_simple_paginator() : $this->implement_numbered_paginator();

        if($json)
            $body[] = [
                    'name' => 'NAME', 'email' => 'EMAIL', 'phone' => 'PHONE', 'gender' => 'GENDER',
                    'country' => 'COUNTRY', 'state' => 'STATE', 'city' => 'CITY', 'address' => 'ADDRESS'
                    ];
        else
            $body[] = [
                        'NAME', 'EMAIL', 'PHONE', 'GENDER',
                        'COUNTRY', 'STATE', 'CITY', 'ADDRESS'
                    ];
        foreach($users as $user) {
            if($json)
                $body[] = [
                    'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone, 'gender' => $user->gender,
                    'country' => $user->country, 'state' => $user->state, 'city' => $user->city, 'address' => $user->address
                ];
            else
                $body[] = [
                    $user->name, $user->email, $user->phone, $user->gender,
                    $user->country, $user->state, $user->city, $user->address
                ];
        }
        
        $this->make_datatable();
        $this->emit('docMake', ['type' => $type, 'body' => $json ? json_encode($body) : $body]);
    }

    private function implement_numbered_paginator()
    {
        if (trim($this->search) == "") {
            return $this->no_search_numbered_paginator();
        } else {
            return $this->with_search_numbered_paginator();
        }

    }

    private function no_search_numbered_paginator()
    {
        if ($this->sort == "columns") {
            return User::orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->paginate($this->fetch);
        }
        elseif ($this->sort == "latest") {
            return User::orderBy('id', 'desc')->paginate($this->fetch);
        }
        else {
            return User::paginate($this->fetch);
        }
    }

    private function with_search_numbered_paginator()
    {
        $q = $this->search;
        if ($this->sort == "columns") {
            return User::where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('gender', 'like', '%' . $q . '%')
                    ->orWhere('country', 'like', '%' . $q . '%')
                    ->orWhere('state', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%');
            })
                ->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')
                ->paginate($this->fetch);
        } elseif ($this->sort == "latest") {
            return User::where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('gender', 'like', '%' . $q . '%')
                    ->orWhere('country', 'like', '%' . $q . '%')
                    ->orWhere('state', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%');
            })
                ->orderBy('id', 'desc')
                ->paginate($this->fetch);
        } else {
            return User::where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('gender', 'like', '%' . $q . '%')
                    ->orWhere('country', 'like', '%' . $q . '%')
                    ->orWhere('state', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%');
            })
                ->paginate($this->fetch);
        }
    }

    private function implement_simple_paginator()
    {
        if (trim($this->search) == "") {
            return $this->no_search_simple_paginator();
        } else {
            return $this->with_search_simple_paginator();
        }

    }

    private function no_search_simple_paginator()
    {
        if ($this->sort == "columns") {
            return User::orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->simplePaginate($this->fetch);
        }
        // Not recommended for large records
        elseif ($this->sort == "latest") {
            return User::orderBy('id', 'desc')->simplePaginate($this->fetch);
        } else {
            return User::simplePaginate($this->fetch);
        }

    }

    private function with_search_simple_paginator()
    {
        $q = $this->search;
        if ($this->sort == "columns") {
            return User::where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('gender', 'like', '%' . $q . '%')
                    ->orWhere('country', 'like', '%' . $q . '%')
                    ->orWhere('state', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%');
            })
                ->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc') // Not recommended for large records
                ->simplePaginate($this->fetch);
        } elseif ($this->sort == "latest") {
            return User::where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('gender', 'like', '%' . $q . '%')
                    ->orWhere('country', 'like', '%' . $q . '%')
                    ->orWhere('state', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%');
            })
                ->orderBy('id', 'desc')
                ->simplePaginate($this->fetch);
        } else {
            return User::where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('phone', 'like', '%' . $q . '%')
                    ->orWhere('gender', 'like', '%' . $q . '%')
                    ->orWhere('country', 'like', '%' . $q . '%')
                    ->orWhere('state', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%');
            })
                ->simplePaginate($this->fetch);
        }

    }

    /**
     * Hooks
    */
    public function updatedFetch($value) {
        $this->make_datatable();
    }
    public function updatedSearch($value) {
        $this->make_datatable();
    }

    /**
     * Public Functions
    */
    public function gotoPage($page)
    {
        $this->page = $page;
        $this->make_datatable();
        $this->emit('showPage', $this->page);
    }

    public function previousPage()
    {
        $this->page > 1 ? $this->page -= 1 : 1;
        $this->make_datatable();
        $this->emit('showPage', $this->page);
    }

    public function nextPage()
    {
        $this->page += 1;
        $this->make_datatable();
        $this->emit('showPage', $this->page);
    }

    public function resort($column) {
        $this->order_by = [$column, !$this->order_by[1]];
        $this->make_datatable();
    }

    public function delete_user($id)
    {
        User::where('id', $id)->delete();
        return true;
    }

    public function pdf_make() {
        $this->export_data_from_table();
    }

    public function table_to_excel() {
        $this->export_data_from_table('excel');
    }

    public function export_to_csv() {
        $this->export_data_from_table('csv', true);
    }

    public function make_datatable() {
        $this->load_state = 'No matching records';
        $this->total = User::count();
        $this->users = $this->total > $this->maxP ? $this->implement_simple_paginator() : $this->implement_numbered_paginator();
        $this->current_page = (($this->page * $this->fetch) - $this->fetch) + 1;
        $remainder = $this->total % $this->fetch;
        $pages = $remainder < 1 ? ($this->total - $remainder) / $this->fetch : (($this->total - $remainder) / $this->fetch) + 1;
        $this->set = $this->page < $pages ? $this->page * $this->fetch : $this->total;
    }

    public function render()
    {
        return view('livewire.datatable', [
            'users' => $this->users
        ]);
    }
}