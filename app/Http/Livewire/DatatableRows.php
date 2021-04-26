<?php

namespace App\Http\Livewire;


use Livewire\Component;

class DatatableRows extends Component
{
    public $user;
    public $edit = false;
    
    public $user_id;
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount() {
        $this->user_id = $this->user->id;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->gender = $this->user->gender;
        $this->country = $this->user->country;
        $this->state = $this->user->state;
        $this->city = $this->user->city;
        $this->address = $this->user->address;
    }

    public function update_user()
    {
        $validatedData = $this->validate();
        User::where('id', $this->user_id)
        ->update($validatedData);

        return true;
    }
    
    public function render()
    {
        return view('livewire.datatable-rows');
    }
}
