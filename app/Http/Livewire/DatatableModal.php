<?php

namespace App\Http\Livewire;
use App\Models\User;

use Livewire\Component;

class DatatableModal extends Component
{
    
    public $user_id;
    public $display = false;
    public $inputs;
    public $input_data;
    
    /***
     *  Livewire V2.4 doesn't support form validation and submission for nested properties
     *  The codes below might be useful if you wish to try
     *************************
     CLASS
     *************************
     *  protected $rules = [
            'input_data' => 'required|array',
            'input_data.*.name' => 'required|string|max:150',
            'input_data.*.email' => 'required|email|max:150',
            'input_data.*.phone' => 'required|string|max:45',
            'input_data.*.gender' => 'required|string|in:male,female',
            'input_data.*.country' => 'required|string|max:150',
            'input_data.*.state' => 'required|string|max:150',
            'input_data.*.city' => 'required|string|max:150',
            'input_data.*.address' => 'required|string|max:150'
        ];
     *************************
     BLADE
     *************************
    *   <div class="modal-header bg-light">
            <h4 class="modal-title">Edit User - {{ $input_data['name'] }}</h4>
        </div>
    *   @foreach ($inputs as $input)
            <div class="col-md-6">
                <div class="form-group">
                    <label for="{{ ucfirst($input['sort']) }}">{{ ucfirst($input['sort']) }}:</label>
                    <input class="form-control @error($input['sort']) is-invalid @enderror" type="text"
                        wire:model="input_data.{{ $input['sort'] }}" />
                    @error($input['sort']) <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
        @endforeach
    *************************
    */

    public $name;
    public $email;
    public $phone;
    public $gender;
    public $country;
    public $state;
    public $city;
    public $address;

    protected $rules = [
        'name' => 'required|string|max:150',
        'email' => 'required|email|max:150',
        'phone' => 'required|string|max:45',
        'gender' => 'required|string|in:male,female',
        'country' => 'required|string|max:150',
        'state' => 'required|string|max:150',
        'city' => 'required|string|max:150',
        'address' => 'required|string|max:150'
    ];
    protected $messages = [
        'name.required' => 'The :attribute cannot be empty.',
        'name.string' => 'The :attribute format is not valid.',
        'name.max' => 'The :attribute length cannot exceed 150.',

        'email.required' => 'The :attribute cannot be empty.',
        'email.email' => 'The :attribute format is not valid.',
        'email.max' => 'The :attribute length cannot exceed 150.',

        'phone.required' => 'The :attribute cannot be empty.',
        'phone.string' => 'The :attribute format is not valid.',
        'phone.max' => 'The :attribute length cannot exceed 45.',

        'gender.required' => 'The :attribute cannot be empty.',
        'gender.string' => 'The :attribute format is not valid.',
        'gender.in' => ':attribute can only be male or female.',

        'country.required' => 'The :attribute cannot be empty.',
        'country.string' => 'The :attribute format is not valid.',
        'country.max' => 'The :attribute length cannot exceed 150.',
        
        'state.required' => 'The :attribute cannot be empty.',
        'state.string' => 'The :attribute format is not valid.',
        'state.max' => 'The :attribute length cannot exceed 150.',
        
        'city.required' => 'The :attribute cannot be empty.',
        'city.string' => 'The :attribute format is not valid.',
        'city.max' => 'The :attribute length cannot exceed 150.',

        'address.required' => 'The :attribute cannot be empty.',
        'address.string' => 'The :attribute format is not valid.',
        'address.max' => 'The :attribute length cannot exceed 150.',
    ];
    protected $validationAttributes = [
        'name' => 'Fullname',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'gender' => 'Gender',
        'country' => 'Country',
        'state' => 'State',
        'city' => 'City',
        'address' => 'Address'
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    private function assign_properties() : array {
        $ary = [];
        foreach($this->inputs as $input) {
            $ary[] = $input['sort'];
        }
        return $ary;
    }

    public function getInputDataProperty() : array {
        return $this->assign_properties();
    }
    public function close_modal() : void {
        $this->display = false;
        $this->emit('closeModal');
    }
    public function edit_user($user) : void {
        $user = (object) $user;
        $this->user_id = $user->id;
        
        foreach($this->inputs as $input) {
            $this->{$input['sort']} = $user->{$input['sort']};
            $this->input_data[$input['sort']] = $user->{$input['sort']};
        }

        $this->display = true;
    }
    public function update_user() : void {
        $validatedData = $this->validate();
        ksort($validatedData);
        ksort($this->input_data);
        try {
            if($validatedData !== $this->input_data) {
                User::where('id', $this->user_id)
                ->update($validatedData);
                $this->input_data = $validatedData;
                $this->emit('reloadDatatable');
                session()->flash('success', 'User has been updated');
            } else {
                session()->flash('fail', 'No changes made');
            }
        } catch (Exception $e) {
            session()->flash('fail', 'User could not be updated');
        }
    }
    public function render() {
        return view('livewire.datatable-modal');
    }
}