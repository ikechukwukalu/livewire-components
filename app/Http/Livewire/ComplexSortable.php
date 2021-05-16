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
        department::where('id', $id)->delete();
    }

    public function removeStaff($id) : void {
        $st = staff::where('id', $id)->first();
        $position = $st->position;
        $dept_id = $st->department_id;
        $st->delete();

        foreach(staff::where('department_id', $dept_id)->where('position', '>', $position)->orderBy('position', 'ASC')->get() as $staff) {
            $staff->position = $position;
            $staff->save();
            $position ++;
        }
    }

    public function render()
    {   
        $this->departments = $this->fetch_department();
        return view('livewire.complex-sortable');
    }
}
