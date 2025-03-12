<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() 
    {
       
        $id=Auth::user()->id;
        
        $user=DB::table('users')->where('id',$id)->first();
        $jaf=DB::table('digital_address_verifications')->where(['candidate_id'=>$user->id,'status'=>'1'])->get();
        // dd($jaf);
       
        return view('candidate.address-verify.home',compact('user','jaf'));
    }

    //Selected Address
    public function homeSelectedAddress(Request $request)
    {
        // dd($request->all());
        $data = DB::table('jaf_form_data')->where(['id'=>$request->jaf_id])->first();
        $input_item_data = $data->form_data;
        $input_item_data_array =  json_decode($input_item_data, true);
        $data=''; 
        foreach($input_item_data_array as $key => $input)
        {
            $key_val = array_keys($input); 
            $input_val = array_values($input);
            if($key_val[0]=='Address')
            {
                $address=$input_val[0];
            }
            if($key_val[0]=='City')
            {
                $city=$input_val[0];
            }
            if($key_val[0]=='State')
            {
                $state=$input_val[0];
            }
            if($key_val[0]=='Pin code')
            {
                $pin=$input_val[0];
            }
        }
        $data.=$address.','.$city.', '.$state.', '.$pin;
        // dd($data);
        return response()->json([
            'fail'      =>false,
            'data' => $data,
            
              ]);
    }
}
