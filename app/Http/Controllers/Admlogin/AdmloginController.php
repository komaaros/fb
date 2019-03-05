<?php

namespace App\Http\Controllers\Admlogin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\UserToCountry;
use Illuminate\Http\Request;

class AdmloginController extends Controller
{
    private $AdmloginUserModel;
    private $CountryModel;
    private $pageCustomJs;
    private $pageVendorJs;
    private $pageVendorCss;

    public function __construct()
    {
        $this->AdmloginUserModel = new User;
        $this->CountryModel = new Country;
        $this->pageCustomJs = [
            'js/admloginIndex.js',
        ];
        $this->pageVendorJs = [
            'assets/vendor/pnotify/pnotify.custom.js',
            'assets/vendor/bootstrap-timepicker/bootstrap-timepicker.js',
        ];
        $this->pageVendorCss = [
            'assets/vendor/pnotify/pnotify.custom.css',
            'assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
        ];
    }

    public function index()
    {
        $data = $this->AdmloginUserModel->read();
        $dataCountry = $this->CountryModel->read();
        return view('admlogin/index', ['data' => $data,
            'pageCustomJs' => $this->pageCustomJs,
            'pageVendorJs' => $this->pageVendorJs,
            'pageVendorCss' => $this->pageVendorCss,
            'dataCountry' => $dataCountry
        ]);
    }
    public function create(Request $request)
    {
        // get data from post
        $data = $request->all();

        // $validatedData = $request->validate([
        //     'name' => '|required|string|max:255',
        //     'email' => 'required|string|email|unique:users|max:255',
        //     'password' => 'required|string|min:6',
        //     'country' => 'required|string|max:255',
        //     'city' => 'required|string|max:255',
        //     'date_of_birth' => 'required|date|before:yesterday|max:255',
        // ]);
        // insert and get last id
        $last_id = $this->AdmloginUserModel->insert($data);
        // find find last inserted item
        $row = $this->AdmloginUserModel->searchById($last_id);
        // get paginator data
        $paginator_data = $this->AdmloginUserModel->read();

        return response()->json(['success' => true, 'rowData' => $row, 'paginatorData' => $paginator_data], 200);
    }

    //edit data
    public function edit(Request $request)
    {
        $data = $request->all();

        $this->AdmloginUserModel->edit($data);

        $paginator_data = $this->AdmloginUserModel->read();
        return response()->json(['success' => true, 'paginatorData' => $paginator_data], 200);
    }
    //delete data
    public function delete(Request $request)
    {
        $data = $request->all();
        $arr = [];
        $detail = $data['formData'];
        foreach (explode('&', $detail) as $item) {
            $parts = explode('=', $item);
            $arr[trim($parts[0])] = $parts[1];
        }

        $currentUrl = str_replace("/delete", "", $data['currentUrl']);
        //die(var_dump($arr['id']));
        $id = $arr['id'];
        $this->AdmloginUserModel->deleteUser($id);

        $paginator_data = $this->AdmloginUserModel->read();
        return response()->json(['success' => true, 'paginatorData' => $paginator_data, 'currentUrl' => $currentUrl], 200);
    }
}
