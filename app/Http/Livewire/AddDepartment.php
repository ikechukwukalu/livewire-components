<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\department;

class AddDepartment extends Component
{
    public $name;
    public $position;

    protected $rules = [
        'name' => 'required|string|max:150|unique:departments',
        'position' => 'required|integer'
    ];
    protected $messages = [
        'name.required' => 'The :attribute cannot be empty.',
        'name.string' => 'The :attribute format is not valid.',
        'name.max' => 'The :attribute length cannot exceed 150.',
        
        'position.required' => 'The :attribute cannot be empty.',
        'position.integer' => 'The :attribute format is not valid.'
    ];
    protected $validationAttributes = [
        'name' => 'Department Name',
        'position' => 'Department Position'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function reset_form() {
        $this->name = null;
        $this->position = null;
    }

    public function addDepartment() : void {
        $this->position = department::count() + 1;
        if($this->position > 9) {
            session()->flash('fail', 'Only 9 departments are allowed');
        } else {
            $validatedData = $this->validate();
            try {
                department::create($validatedData);
                session()->flash('success', 'New department created');
                $this->reset_form();
                $this->emit('updatesMade');
            } catch (Exception $e) {
                session()->flash('fail', 'Department could not be created');
            }
        }
    }

    public function render()
    {
        return view('livewire.add-department');
    }
}
