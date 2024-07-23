<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProfileModel extends Model
{
    use HasFactory;

    function getProfile()
    {
        $profiles = DB::table('employees')
            ->join('account', 'employees.id_employee', '=', 'account.id_employee')
            ->join('job_detail', 'employees.id_employee', '=', 'job_detail.id_employee')
            ->join('job_position', 'job_detail.id_job_position', '=', 'job_position.id_position')
            ->join('job_country', 'job_detail.id_job_country', '=', 'job_country.id_country')
            ->join('contacts', 'employees.id_contact', '=', 'contacts.id_contact')
            ->where('employees.id_employee', \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID))
            ->first();
        return ['profiles' => $profiles];
    }

    function updateProfile($id_account)
    {
        $query =
            "
                    update employees, job_detail, job_position, job_country, contacts, account
                    set
                        employees.first_name = :first_name,
                        employees.last_name = :last_name,
                        job_position.position_name = :position_name,
                        job_country.country_name = :country_name,
                        contacts.permanent_address = :permanent_address,
                        contacts.phone_number = :phone_number,
                        job_detail.email = :email
                    where
                        employees.id_employee = account.id_employee AND
                        employees.id_employee = job_detail.id_employee AND
                        job_detail.id_job_position = job_position.id_position AND
                        job_detail.id_job_country = job_country.id_country AND
                        employees.id_contact = contacts.id_contact AND
                        account.id_employee = $id_account
                ";

        $par = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position_name' => $this->position_name,
            'country_name' => $this->country_name,
            'permanent_address' => $this->permanent_address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
        ];
        return DB::update($query, $par);
    }
}
