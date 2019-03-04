<?php

namespace App\Models\Admlogin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    const UPDATED_AT = null;
    protected $fillable = [
        "name",
        "email",
        "password",
        "country",
        "city",
        "date_of_birth"
    ];

    public function read()
    {

        $result = User::paginate(3);
        return $result;
    }

    // insert values into users table
    public function insert($data)
    {
        $user = new User;
        foreach ($data as $key => $value) {
            // skip _token field
            if ($key !== "_token") {
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

        return $user->id;
    }

    public function edit($data) {
        $user = User::find($data['id']);

        foreach ($data as $key => $value) {
            if ($key == "_token" || $key == 'id') {
 
            } else {
                if ($key == "password" || !empty($value["password"])) {
                    $password_hashed = Hash::make($value);
                    $user->$key = $password_hashed;
                } else {
                    $user->$key = $value;
                }
            }
        }
        $user->save();
        
    }

    public function deleteUser($id)
    {
        $user = new static;
        $row = $user::find($id);
        $row->delete();
    }

    public function searchById($id)
    {
        $user = new static;
        $row = $user::find($id);
        return $row;
    }

}
