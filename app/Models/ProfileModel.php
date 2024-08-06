<?php

namespace App\Models;

use App\Traits\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProfileModel extends Model
{
    use HasFactory;
    use Profile;

    function getProfile()
    {
        $profiles = DB::table('accounts')
            ->join('employees', 'employees.employee_id', '=', 'accounts.employee_id')
            ->join('job_details', 'employees.employee_id', '=', 'job_details.employee_id')
            ->join('job_positions', 'job_details.job_position_id', '=', 'job_positions.position_id')
            ->join('job_countries', 'job_details.job_country_id', '=', 'job_countries.country_id')
            ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
            ->where('accounts.account_id', \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID))
            ->first();
        return ['profiles' => $profiles];
    }

    function updateProfile()
    {
        $query =
            "
                UPDATE employees
                INNER JOIN job_details ON employees.employee_id = job_details.employee_id
                INNER JOIN contacts ON employees.contact_id = contacts.contact_id
                INNER JOIN accounts ON employees.employee_id = accounts.employee_id
                SET
                    employees.first_name = :first_name,
                    employees.last_name = :last_name,
                    job_details.job_position_id = :position_name,
                    job_details.job_country_id = :country_name,
                    contacts.permanent_address = :permanent_address,
                    contacts.phone_number = :phone_number,
                    accounts.email = :email,
                    accounts.username = :username
                WHERE accounts.employee_id = :employee_id
           ";

        $par = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position_name' => $this->position_name,
            'country_name' => $this->country_name,
            'permanent_address' => $this->permanent_address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'username' => $this->username,
            'employee_id'=>$this->employee_id
        ];

        return DB::update($query, $par);
    }
}
