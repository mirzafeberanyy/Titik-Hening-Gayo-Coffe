<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class MyProfile extends Component
{
    // Data Diri
    public $name, $email, $phone;

    // Password
    public $current_password, $new_password, $new_password_confirmation;

    // Alamat Default
    public $selectedProvince, $selectedCity, $selectedDistrict, $address;

    public function mount()
    {
        $user = auth()->user();
        
        // Isi form dengan data database
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        
        $this->selectedProvince = $user->province_id;
        $this->selectedCity = $user->city_id;
        $this->selectedDistrict = $user->district_id;
        $this->address = $user->address;
    }

    // --- LOGIC WILAYAH ---
    public function getProvincesProperty() { return Province::pluck('name', 'code'); }
    
    public function getCitiesProperty() {
        if(!$this->selectedProvince) return [];
        return City::where('province_code', $this->selectedProvince)->pluck('name', 'code');
    }

    public function getDistrictsProperty() {
        if(!$this->selectedCity) return [];
        return District::where('city_code', $this->selectedCity)->pluck('name', 'code');
    }

    public function updatedSelectedProvince() { $this->selectedCity = null; $this->selectedDistrict = null; }
    public function updatedSelectedCity() { $this->selectedDistrict = null; }

    // --- UPDATE PROFIL & ALAMAT ---
    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'phone' => 'required|numeric',
            'selectedProvince' => 'nullable',
            'selectedCity' => 'nullable',
            'selectedDistrict' => 'nullable',
            'address' => 'nullable|min:10',
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'province_id' => $this->selectedProvince,
            'city_id' => $this->selectedCity,
            'district_id' => $this->selectedDistrict,
            'address' => $this->address,
        ]);

        session()->flash('success', 'Profil dan Alamat berhasil diperbarui!');
    }

    // --- UPDATE PASSWORD ---
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:6|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('success_password', 'Password berhasil diubah!');
    }

    public function render()
    {
        return view('livewire.my-profile');
    }
}