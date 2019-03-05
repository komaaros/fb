<?php

namespace App\Models;

use App\Models\Country;
use App\Models\UserToCountry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    const UPDATED_AT = null;
    private $UserToCountryModel;
    private $CountryModel;

    public function __construct()
    {
        $this->UserToCountryModel = new UserToCountry;
        $this->CountryModel = new Country;
    }

    public function read()
    {

        //$result = User::paginate(3);
        // $result = User::Leftjoin('users_to_countries', 'users.id', '=', 'users_to_countries.user_id')
        //     ->select('users.*', 'users_to_countries.name AS countryName', 'users_to_countries.country_id AS country_id' )->paginate(3);
        $result = UserToCountry::Leftjoin('countries', 'users_to_countries.country_id', '=', 'countries.id')
        ->Rightjoin('users', 'users.id', '=', 'users_to_countries.user_id')
        ->select('users.*', 'countries.name AS countryName', 'users_to_countries.country_id AS country_id')->paginate(3);
        return $result;
    }

    // insert values into users table
    public function insert($data)
    {
        $user = new User;
        foreach ($data as $key => $value) {
            // skip _token field
            if ($key == "_token" || $key == "country") {
                continue;
            } else {
                // hash password
                if ($key == "password") {
                    $password_hashed = Hash::make($value);
                    $user->$key = $password_hashed;
                } else {
                    $user->$key = $value;
                }
            }
        }
        $user->save();

        $this->UserToCountryModel->user_id = $user->id;
        $this->UserToCountryModel->country_id = $data['country'];
        $this->UserToCountryModel->save();

        return $user->id;
    }

    public function edit($data)
    {
        $user = User::find($data['id']);
        $user_to_country = UserToCountry::where('user_id', $data['id'])->first();

        foreach ($data as $key => $value) {
            if ($key == "_token" || $key == 'id' || $key == "country") {
                continue;
            } else {
                if ($key == "password" || !empty($value["password"])) {
                    $password_hashed = Hash::make($value);
                    $user->$key = $password_hashed;
                } else {
                    $user->$key = $value;
                }
            }
        }
        $user_to_country->country_id = $data['country'];
        
        
        $user_to_country->save();
        $user->save();

    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user_to_country = $this->UserToCountryModel->where('user_id', $id)->first();
        $user->delete();
        $user_to_country->delete();
    }

    public function searchById($id)
    {
        $user = User::find($id);
        return $user;
    }

}
