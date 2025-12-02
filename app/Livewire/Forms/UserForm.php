<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use App\Models\EmployeeDetail;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserForm extends Form
{
    public ?User $user = null;

    public $name = '';
    public $nip = '';
    public $email = '';
    public $phone = '';
    public $password = null;
    public $gender = null;
    public $city = '';
    public $address = '';
    public $group = 'user';
    public $birth_date = null;
    public $birth_place = '';
    public $division_id = null;
    public $education_id = null;
    public $job_title_id = null;
    public $photo = null;

    // Employee Details fields
    public $nik = '';
    public $npwp = '';
    public $bpjs_tk = '';
    public $bpjs_kes = '';
    public $marital_status = null;
    public $phone_emergency = '';
    public $join_date = null;
    public $end_contract_date = null;
    public $employment_status = 'probation';
    public $bank_name = '';
    public $bank_account_number = '';

    public function rules()
    {
        $requiredOrNullable = $this->group === 'user' ? 'required' : 'nullable';
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->user)
            ],
            'nip' => [$requiredOrNullable, 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user)
            ],
            'phone' => ['required',  'string', 'min:5', 'max:255'],
            'password' => ['nullable', 'string', 'min:4', 'max:255'],
            'gender' => [$requiredOrNullable, 'in:male,female'],
            'city' => [$requiredOrNullable, 'string', 'max:255'],
            'address' => [$requiredOrNullable, 'string', 'max:255'],
            'group' => ['nullable', 'string', 'max:255', Rule::in(User::$groups)],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'education_id' => ['nullable', 'exists:educations,id'],
            'job_title_id' => ['nullable', 'exists:job_titles,id'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            // Employee Details validation
            'nik' => [
                'nullable',
                'string',
                'max:255',
                // Only validate uniqueness if nik is not empty (empty strings will be converted to null)
                function ($attribute, $value, $fail) {
                    if (!empty($value) && $value !== '') {
                        $query = \App\Models\EmployeeDetail::where('nik', $value);
                        if ($this->user?->employeeDetail?->id) {
                            $query->where('id', '!=', $this->user->employeeDetail->id);
                        }
                        if ($query->exists()) {
                            $fail('The NIK has already been taken.');
                        }
                    }
                },
            ],
            'npwp' => ['nullable', 'string', 'max:255'],
            'bpjs_tk' => ['nullable', 'string', 'max:255'],
            'bpjs_kes' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'phone_emergency' => ['nullable', 'string', 'max:255'],
            'join_date' => ['nullable', 'date'],
            'end_contract_date' => ['nullable', 'date'],
            'employment_status' => ['nullable', 'in:contract,permanent,probation'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->nip = $user->nip;
        $this->email = $user->email;
        $this->phone = $user->phone;
        if ($this->isAllowed()) {
            $this->password = $user->raw_password;
        }
        $this->gender = $user->gender;
        $this->city = $user->city;
        $this->address = $user->address;
        $this->group = $user->group;
        $this->birth_date = $user->birth_date
            ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('Y-m-d')
            : null;
        $this->birth_place = $user->birth_place;
        $this->division_id = $user->division_id;
        $this->education_id = $user->education_id;
        $this->job_title_id = $user->job_title_id;
        
        // Load employee details if exists
        if ($user->employeeDetail) {
            $detail = $user->employeeDetail;
            $this->nik = $detail->nik ?? '';
            $this->npwp = $detail->npwp ?? '';
            $this->bpjs_tk = $detail->bpjs_tk ?? '';
            $this->bpjs_kes = $detail->bpjs_kes ?? '';
            $this->marital_status = $detail->marital_status;
            $this->phone_emergency = $detail->phone_emergency ?? '';
            $this->join_date = $detail->join_date ? \Illuminate\Support\Carbon::parse($detail->join_date)->format('Y-m-d') : null;
            $this->end_contract_date = $detail->end_contract_date ? \Illuminate\Support\Carbon::parse($detail->end_contract_date)->format('Y-m-d') : null;
            $this->employment_status = $detail->employment_status ?? 'probation';
            $this->bank_name = $detail->bank_name ?? '';
            $this->bank_account_number = $detail->bank_account_number ?? '';
        }
        
        return $this;
    }

    public function store()
    {
        if (!$this->isAllowed()) {
            return abort(403);
        }
        $this->validate();
        /** @var User $user */
        $userData = $this->only([
            'name', 'nip', 'email', 'phone', 'gender', 'city', 'address',
            'group', 'birth_date', 'birth_place', 'division_id', 'education_id', 'job_title_id'
        ]);
        $userData['password'] = Hash::make($this->password ?? 'password');
        $userData['raw_password'] = $this->password ?? 'password';
        
        $user = User::create($userData);
        
        if (isset($this->photo)) $user->updateProfilePhoto($this->photo);
        
        // Create employee details
        $employeeDetailData = $this->only([
            'nik', 'npwp', 'bpjs_tk', 'bpjs_kes', 'marital_status', 'phone_emergency',
            'join_date', 'end_contract_date', 'employment_status', 'bank_name', 'bank_account_number'
        ]);
        
        // Convert empty strings to null for unique nullable fields
        $employeeDetailData = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $employeeDetailData);
        
        $employeeDetailData['user_id'] = $user->id;
        EmployeeDetail::create($employeeDetailData);
        
        $this->reset();
    }

    public function update()
    {
        if (!$this->isAllowed()) {
            return abort(403);
        }
        $this->validate();
        
        // Check if user is updating their own profile (not admin)
        $isOwnProfile = Auth::user()?->id === $this->user->id && !Auth::user()?->isAdmin;
        
        if ($isOwnProfile) {
            // User can only update these fields (not NIP, division, job_title, etc.)
            $userData = $this->only([
                'name', 'email', 'phone', 'gender', 'city', 'address',
                'birth_date', 'birth_place'
            ]);
            // Don't update NIP for own profile
            unset($userData['nip']);
        } else {
            // Admin can update all fields
            $userData = $this->only([
                'name', 'nip', 'email', 'phone', 'gender', 'city', 'address',
                'group', 'birth_date', 'birth_place', 'division_id', 'education_id', 'job_title_id'
            ]);
        }
        
        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
            $userData['raw_password'] = $this->password;
        }
        
        $this->user->update($userData);
        
        if (isset($this->photo)) $this->user->updateProfilePhoto($this->photo);
        
        // Update or create employee details
        $employeeDetailData = $this->only([
            'nik', 'npwp', 'bpjs_tk', 'bpjs_kes', 'marital_status', 'phone_emergency',
            'join_date', 'end_contract_date', 'employment_status', 'bank_name', 'bank_account_number'
        ]);
        
        // Convert empty strings to null for unique nullable fields
        $employeeDetailData = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $employeeDetailData);
        
        // User cannot update employment-related fields
        if ($isOwnProfile) {
            unset($employeeDetailData['join_date']);
            unset($employeeDetailData['end_contract_date']);
            unset($employeeDetailData['employment_status']);
        }
        
        if ($this->user->employeeDetail) {
            $this->user->employeeDetail->update($employeeDetailData);
        } else {
            $employeeDetailData['user_id'] = $this->user->id;
            EmployeeDetail::create($employeeDetailData);
        }
        
        $this->reset();
    }

    public function deleteProfilePhoto()
    {
        if (!$this->isAllowed()) {
            return abort(403);
        }
        return $this->user->deleteProfilePhoto();
    }

    public function delete()
    {
        if (!$this->isAllowed()) {
            return abort(403);
        }
        
        if (!$this->user) {
            throw new \RuntimeException('User not set. Use setUser() before deleting.');
        }
        
        // Delete related data first
        // Delete attendances
        Attendance::where('user_id', $this->user->id)->delete();
        
        // Delete leave requests (already has cascade, but delete explicitly for safety)
        LeaveRequest::where('user_id', $this->user->id)->delete();
        
        // Delete payroll details first (because payroll_details has foreign key to payrolls)
        $payrollIds = \App\Models\Payroll::where('user_id', $this->user->id)->pluck('id');
        if ($payrollIds->isNotEmpty()) {
            \App\Models\PayrollDetail::whereIn('payroll_id', $payrollIds)->delete();
        }
        
        // Delete payrolls
        \App\Models\Payroll::where('user_id', $this->user->id)->delete();
        
        // Delete employee salaries
        \App\Models\EmployeeSalary::where('user_id', $this->user->id)->delete();
        
        // Delete employee details (already has cascade, but delete explicitly for safety)
        if ($this->user->employeeDetail) {
            $this->user->employeeDetail->delete();
        }
        
        // Delete profile photo
        $this->deleteProfilePhoto();
        
        // Finally delete the user
        $this->user->delete();
        
        $this->reset();
    }

    private function isAllowed()
    {
        // If updating own profile, allow it
        if ($this->user && Auth::user()?->id === $this->user->id) {
            return true;
        }
        
        if ($this->group === 'user') {
            return Auth::user()?->isAdmin;
        }
        return Auth::user()?->isSuperadmin || (Auth::user()?->isAdmin && Auth::user()?->id === $this->user?->id);
    }
}
