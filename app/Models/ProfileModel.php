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
        $profiles = DB::table('account')
            ->join('employees', 'employees.id_employee', '=', 'account.id_employee')
            ->join('job_detail', 'employees.id_employee', '=', 'job_detail.id_employee')
            ->join('job_position', 'job_detail.id_job_position', '=', 'job_position.id_position')
            ->join('job_country', 'job_detail.id_job_country', '=', 'job_country.id_country')
            ->join('contacts', 'employees.id_contact', '=', 'contacts.id_contact')
            ->where('account.id_account', \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID))
            ->first();
        return ['profiles' => $profiles];
    }

    function updateProfile()
    {
        $query =
            "
                UPDATE employees
                INNER JOIN job_detail ON employees.id_employee = job_detail.id_employee
                INNER JOIN contacts ON employees.id_contact = contacts.id_contact
                INNER JOIN account ON employees.id_employee = account.id_employee
                SET
                    employees.first_name = :first_name,
                    employees.last_name = :last_name,
                     job_detail.id_job_position = :position_name,
                        job_detail.id_job_country = :country_name,
                    contacts.permanent_address = :permanent_address,
                    contacts.phone_number = :phone_number,
                    account.email = :email
                WHERE account.id_employee = :id_employee
           ";

        $par = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position_name' => $this->position_name,
            'country_name' => $this->country_name,
            'permanent_address' => $this->permanent_address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'id_employee'=>$this->id_employee
        ];

        return DB::update($query, $par);
    }
}
