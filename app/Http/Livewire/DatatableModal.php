<?php

namespace App\Http\Livewire;
use App\Models\User;

use Livewire\Component;

class DatatableModal extends Component
{
    
    public $user_id;
    public $display = false;
    public $inputs;
    
    /***
     *  Livewire V2.4 doesn't support form validation and submission of nested properties
     *  The codes below might be useful if you wish to try
     *************************
     CLASS
     *************************
     *  public $input_data;
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
        ]
     *  private function assign_properties() {
            $ary = [];
            foreach($this->inputs as $input) {
                $ary[] = $input['sort'];
            }
            return $ary;
        }
    *   public function getInputDataProperty() {
            return $this->assign_properties();
        }
    *   public function edit_user($user) {
            $user = (object) $user;
            $this->user_id = $user->id;
            
            foreach($this->inputs as $input) {
                $this->input_data[$input['sort']] = $user->{$input['sort']};
            }

            $this->display = true;
            return true;
        }
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
    * You would need to manually type out the needed columns you wish to update
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

    public function close_modal() {
        $this->display = false;
        $this->emit('closeModal');
    }
    public function edit_user($user) {
        $user = (object) $user;
        $this->user_id = $user->id;
        
        foreach($this->inputs as $input) {
            $this->{$input['sort']} = $user->{$input['sort']};
        }

        $this->display = true;
        return true;
    }
    public function update_user() {
        $validatedData = $this->validate();
        try {
            User::where('id', $this->user_id)
            ->update($validatedData);
            $this->emit('reloadDatatable');
            session()->flash('success', 'User has been updated');
        } catch (Exception $e) {
            session()->flash('fail', 'User could not be updated');
        }
        return true;
    }
    public function render() {
        return view('livewire.datatable-modal');
    }
}