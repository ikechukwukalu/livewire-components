<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\department;
use App\Models\staff;

class ComplexSortable extends Component
{
    public $departments;

    protected $listeners = ['updatesMade' => '$refresh'];

    private function fetch_department() : object {
        return department::orderBy('position', 'ASC')->get();
    }

    public function departmentOrder($list) : void {
        foreach($list as $item) {
            department::find($item['value'])->update(['position' => $item['order']]);
        }
    }

    public function staffOrder($list) : void {
        foreach($list as $item) {
            department::find($item['value'])->update(['position' => $item['order']]);
            foreach($item['items'] as $t) {
                staff::find($t['value'])->update(['position' => $t['order'], 'department_id' => $item['value']]);
            }
        }
    }

    public function removeDepartment($id) : void {
        foreach(staff::where('department_id', $id)->get() as $staff) {
            $staff->delete();
        }
        department::find($id)->delete();
    }

    public function removeStaff($id) : void {
        staff::find($id)->delete();
    }

    public function render()
    {   
        $this->departments = $this->fetch_department();
        return view('livewire.complex-sortable');
    }
}
