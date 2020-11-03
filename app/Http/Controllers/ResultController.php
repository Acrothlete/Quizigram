<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Result;
use App\Models\Question;
use App\Models\Test;



class ResultController extends Controller
{
    public function index(){
        $tests = Test::all();
        return view('result',['tests' => $tests]);
    }

    public function generate_result(Request $request, $test_id){
        //$test_id = 1;

        //getting total marks of particular test using test_id
        $total_marks = 0;
        $test_question = Question::where('test_id',$test_id)->get();
    
        //storing sum of particular test in total_marks
        foreach($test_question as $tq){
            $total_marks = $total_marks + $tq->marks;
        }

        //if total marks 0 then no question available to database
        if($total_marks == 0){
            abort(404);
        }

        $test = Test::find($test_id);

        //deleting the existing result for specified test id if present from result table
        DB::table('results')->where('test_id', $test_id)->delete();
   
        //getting each user from answer table 
        //$user_id = DB::table('answers')->distinct('user_id')->get();
        //$user = Answer::distict('user_id');
        $users = DB::select('SELECT DISTINCT user_id FROM answers');

        //forloop start for user from here
        foreach($users as $user) {
            //echo $user->user_id;
            //$user_id = 1;

            //get all the users id from which are present in answer table
            $user_answer_data = Answer::where([ 'test_id'=>$test_id, 'user_id'=>$user->user_id])->get();

            $marks_obtained_by_user = 0;
            foreach($user_answer_data as $user) {
                if($user->actual_answer == $user->user_answer){
                    $marks_obtain_in_one_question = $user->marks_of_quetion;
                    $marks_obtained_by_user = $marks_obtained_by_user + $marks_obtain_in_one_question;
                }                       
            }

            $percentage_obtained_by_user = ($marks_obtained_by_user * 100)/$total_marks;

            $result = "PASS";
            if($percentage_obtained_by_user <= $test->passing_percentage){
                $result = "FAIL";
            }

            //storing result to result database
            Result::updateOrCreate(
                ['user_id' => $user->user_id, 'test_id' => $test_id,'test_name' => $test->test_name],
                ['obtained_marks' => $marks_obtained_by_user,'total_marks' => $total_marks, 'obtained_percentage'=> $percentage_obtained_by_user, 'result' => $result ]
                );
            }
            
            $results = Result::where('test_id', $test_id)->with('user_data')->get();
        //return $user_answer_data;
        return view('test_result', ['results' => $results]);
        //return 'generating result';


    }
}
