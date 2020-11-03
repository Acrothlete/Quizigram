<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UserExport;
use App\Models\Test;
use Validator;
use app\Models\User;
use Maatwebsite\Excel\facades\Excel;


// use Maatwebsite\Excel\


class TestController extends Controller
{
    public function admin_landing(){
        return view('admin_landing_page');
    }

    public function export() 
{
          
    return Excel::download(new UserExport, 'user.xlsx');
}

    public function index(){
        $tests = Test::all();
        return view('test', ['tests' => $tests]);
    }
    public function create_test(Request $request){
        
        $validator = Validator::make($request->all(), [
            'test_name' => 'required',
            'test_limit' => 'required',
            'passing_percentage'=> 'required',
        ]);

        
        if ($validator->fails()) {
            return redirect('admin/create-test')
                        ->withErrors($validator)
                        ->withInput();
        }

        $test = new Test();
        $test->test_name = $request->test_name;
        $test->time_limit = $request->test_limit;
        $test->passing_percentage = $request->passing_percentage;
        
        $test->save();
        
        return redirect('admin/test'); 
    }

    public function add_test(){
        return view('add_test');
        // return view('welcome');
    }
    
    public function user_test_landing($id){
        $test = Test::find($id);

        //if test exists then it will render start test screen
        if($test){
            return view('starttest',['test'=>$test]);
        }
        else{
            abort(404, 'Requested resource is not available');

        }
    }

    public function test_success(){
        return view('test_Successfull');        
    }
}
