<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Datatable extends Component
{
    use WithPagination;

    public $page_options;
    public $pages_displayed;
    public $order_by;
    public $search;
    public $sort;
    public $maxP;

    protected $queryString = ['search', 'pages_displayed'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteUser' => 'delete_user', 'reloadDatatable' => 'render'];

    public function gotoPage($page)
    {
        $this->page = $page;
        $this->emit('showPage', $this->page);
    }

    public function previousPage()
    {
        $this->page > 1 ? $this->page -= 1 : 1;
        $this->emit('showPage', $this->page);
        return true;
    }

    public function nextPage()
    {
        $this->page += 1;
        $this->emit('showPage', $this->page);
        return true;
    }

    public function delete_user($id)
    {
        User::where('id', $id)->delete();
        return true;
    }

    private function implement_numbered_paginator()
    {
        if (trim($this->search) == "") {
            return $this->with_search_numbered_paginator();
        } else {
            return $this->no_search_numbered_paginator();
        }

    }

    private function no_search_numbered_paginator()
    {
        if ($this->sort == "columns") {
            return User::orderBy($this->order_by[0], $this->order_by[1])->paginate($this->pages_displayed);
        }
        // Not recommended for large records
        elseif ($this->sort == "latest") {
            return User::orderBy('id', 'desc')->paginate($this->pages_displayed);
        }
        // 2nd best, but not for records that exceeds 5k
        else {
            return User::paginate($this->pages_displayed);
        }
        // Best performance, but not for records that exceeds 5k
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
                ->orderBy($this->order_by[0], $this->order_by[1]) // Not recommended for large records
                ->paginate($this->pages_displayed);
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
                ->orderBy('id', 'desc') // Not for records that exceeds 5k
                ->paginate($this->pages_displayed);
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
                ->paginate($this->pages_displayed);
        }
        // Not for records that exceeds 5k
    }

    private function implement_simple_paginator()
    {
        if (trim($this->search) == "") {
            return $this->with_search_simple_paginator();
        } else {
            return $this->no_search_simple_paginator();
        }

    }

    private function no_search_simple_paginator()
    {
        if ($this->sort == "columns") {
            return User::orderBy($this->order_by[0], $this->order_by[1])->simplePaginate($this->pages_displayed);
        }
        // Not recommended for large records
        elseif ($this->sort == "latest") {
            return User::orderBy('id', 'desc')->simplePaginate($this->pages_displayed);
        } else {
            return User::simplePaginate($this->pages_displayed);
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
                ->orderBy($this->order_by[0], $this->order_by[1]) // Not recommended for large records
                ->simplePaginate($this->pages_displayed);
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
                ->simplePaginate($this->pages_displayed);
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
                ->simplePaginate($this->pages_displayed);
        }

    }

    public function render()
    {
        $total = User::count();
        $users = $total > $this->maxP ? $this->implement_simple_paginator() : $this->implement_numbered_paginator();
        $page = (($this->page * $this->pages_displayed) - $this->pages_displayed) + 1;
        $remainder = $total % $this->pages_displayed;
        $pages = $remainder < 1 ? ($total - $remainder) / $this->pages_displayed : (($total - $remainder) / $this->pages_displayed) + 1;
        $set = $this->page < $pages ? $this->page * $this->pages_displayed : $total;

        return view('livewire.datatable', [
            'users' => $users,
            'showing' => [
                'page' => number_format($page),
                'set' => number_format($set),
                'total' => number_format($total),
            ],
        ]);
    }
}
