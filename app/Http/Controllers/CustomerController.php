<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Validator;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $customers = Customer::paginate(1);

        // return response()->json([
        //     'data'=>$customers
        // ]);

        $customers = Customer::all();
        // dd( $customers );
        if ($customers) {
            // $data = new ApiFormatter();
            $data = (new ApiFormatter)->createApi(200, 'Success', $customers);
            return $data;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()              
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $customer = Customer::create([
            'name' => $request->name,
            'id_number' => $request->id_number,
            'dob' => $request->dob,
            'email' => $request->email,
        ]);

        return response()->json(
        [
            'data' => $customer
        ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
        if ($customer) {
            $code = 200;
        }
        else {
            $code = 407;
        }
        // dd($customer);
        return response()->json($customer, $code);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
        $customer->name = $request->name;
        $customer->id_number = $request->id_number;
        $customer->dob = $request->dob;
        $customer->email = $request->email;
        $customer->save();
        return response()->json([
            'data' => $customer
        ]);
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();
        return response()->json([
            'message' => 'customer deleted'
        ], 204);
    }

    function DataValidation(Request $request)
    {
        $rules = [
            "name" => "required",
            "user_id" => "required",
            "dop" => "required",
            "email" => "required",
        ];
        $validator = $this->validate($request, $rules);
    }


    public function upload(Request $request)
    {

        $request->validate([
            'file' => 'required|max:100',
            'fileName'=>'required'
        ]);
        
        $files = $request->file('file');
        $path = $files->storeAs('uploads', $files->getClientOriginalName(),'local-upload'); //Default location will be in "storage/app/public"
        
        return response()->json([
            'message' => 'success uploaded !',
            'path' => $path,
        ],201);
    }

    public function download(Request $request)
    { 
        $baseDir = Storage::disk('local-upload');
        $chekDir = $baseDir->exists('uploads');
        if ($chekDir) {
            try {
                $path = $baseDir->path("uploads/$request->filename");  
                return response()->download($path); 
            } catch (\Throwable $th) {
                return response()->json([
                    'message'=>'File Not Found'
                ],404);
            } 
        } 
    }


}