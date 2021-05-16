<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\staff;

class AddStaff extends Component
{
    public $name;
    public $position;
    public $department_id;

    protected $rules = [
        'name' => 'required|string|max:150|unique:staffs',
        'position' => 'required|integer',
        'department_id' => 'required|integer'
    ];
    protected $messages = [
        'name.required' => 'The :attribute cannot be empty.',
        'name.string' => 'The :attribute format is not valid.',
        'name.max' => 'The :attribute length cannot exceed 150.',
        
        'position.required' => 'The :attribute cannot be empty.',
        'position.integer' => 'The :attribute format is not valid.',
        
        'department_id.required' => 'The :attribute cannot be empty.',
        'department_id.integer' => 'The :attribute format is not valid.'
    ];
    protected $validationAttributes = [
        'name' => 'Staff Name',
        'position' => 'Staff Position',
        'department_id' => 'Staff Department'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function reset_form() {
        $this->name = null;
        $this->position = null;
    }

    public function addStaff() : void {
        $this->position = staff::where('department_id', $this->department_id)->count() + 1;
        if($this->position > 9) {
            session()->flash('fail', 'Only 9 staffs are allowed');
        } else {
            $validatedData = $this->validate();
            try {
                staff::create($validatedData);
                session()->flash('success', 'New staff created');
                $this->reset_form();
                $this->emit('updatesMade');
            } catch (Exception $e) {
                session()->flash('fail', 'Staff could not be created');
            }
        }
    }
    public function render()
    {
        return view('livewire.add-staff');
    }
}
