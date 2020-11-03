<?php

namespace App\Http\Controllers;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class QuestionController extends Controller
{
    public function index(Request $request,$id)
    {   
        //getting user id
        $user = User::find(Auth::user()->id);

        //add condition if time expired then redirect to submit exam page
        if($user->test_end_at < Carbon::now()->setTimezone('Asia/Kolkata') || $user->is_test_submitted){
            return redirect('/test/success');
        } 

        //getting question id
        $question_id = $request->get('page',1);

        //check if user has already answered this question or not
        $user_answered_raw =  DB::table('answers')->where([
            ['user_id', $user->id],
            ['question_id', $question_id],
            ['test_id', $id],
            ])->first();
        
        //if already answered then getting that value    
        if($user_answered_raw){
            $user_answered_value = $user_answered_raw->user_answer;
        }
        else{
            $user_answered_value = null;
        }    

        // $question = Question::all();
        $question = DB::table('questions')->where('test_id',$id)->paginate(1);

        //getting value of test end
        $test_end_at = User::find(Auth::user()->id)->test_end_at;
        
        return view('question', ['questions' => $question,'test_end_at' => $test_end_at,'test_id'=> $id,'user_answered_value' => $user_answered_value]);
        
    }

    public function save_answer(Request $request,$id){
        //get user
        $user = User::find(Auth::user()->id);

        //get last question number
        $last_question = $request->last_question;

        //get question,answer
        $question_id = $request->question_id;
        $user_answer = $request->answer;

        //creating variable for going to next page
        $next_page = $question_id + 1;

        //redirecting to next question if user has not selected any option
        //dd($request->page);

        if(!$user_answer){
            if($question_id == $last_question){
                return redirect('/test/'.$id.'/question?page=1');
            }
            else{
                return redirect('/test/'.$id.'/question?page='.$next_page);    
            }
        }

        //getting the actual answer of question and marks
        $question_raw = Question::find($question_id);
        $actual_answer = $question_raw->answer;
        $marks_of_question = $question_raw->marks;
        
        // //checking if answer is already submitted by user or not 

        Answer::updateOrCreate(
        ['user_id' => $user->id, 'test_id' => $id,'question_id' => $question_id,'actual_answer' => $actual_answer,'marks_of_quetion' => $marks_of_question ],
        ['user_answer' => $user_answer ]
        );

        //add condition if time expired then redirect to submit exam page
        if($user->test_end_at > Carbon::now()->setTimezone('Asia/Kolkata')){
            if($question_id == $last_question){
                return redirect('/test/'.$id.'/question?page=1');
            }
            else{
                return redirect('/test/'.$id.'/question?page='.$next_page);    
            }
        }else{
            return redirect('/test/success');
        }
        return redirect('/test/'.$id.'/question?page='.$next_page);
    }

    public function save_and_submit(Request $request,$id){
        //get user
        $user = User::find(Auth::user()->id);

        //get question,answer
        $question_id = $request->question_id;
        $user_answer = $request->answer;


        //if user has clicked answer then save it
        if($user_answer){  
            //getting the actual answer of question and marks
            $question_raw = Question::find($question_id);
            $actual_answer = $question_raw->answer;
            $marks_of_question = $question_raw->marks;
            
            // //checking if answer is already submitted by user or not
            
            //user ,column, boolean, 

            Answer::updateOrCreate(
            ['user_id' => $user->id, 'test_id' => $id,'question_id' => $question_id,'actual_answer' => $actual_answer,'marks_of_quetion' => $marks_of_question ],
            ['user_answer' => $user_answer ]
            );
        }

        //set is_test_submitted to true
        DB::table('users')->where('id', $user->id)->update(
            ['is_test_submitted' => true]
        );

        

        //update column in user table that test has submitted successfully by user

        return redirect('/test/success');
    }



    public function start_test(Request $request,$test_id){
        
        //find user
        $user = User::find(Auth::user()->id);
        
        //get minutes of test
        $test_duration = Test::find(1)->time_limit;

        //started at variable in datetime
        $test_started_at = Carbon::now()->setTimezone('Asia/Kolkata');
    
        //ended at variable in datetime
        $test_end_at =  Carbon::parse($test_started_at)->addMinutes($test_duration); 

        //store details to database
        DB::table('users')->where('id', $user->id)->update(
            ['test_started_at' => $test_started_at , 'test_end_at' => $test_end_at, 'is_test_submitted' => false]
        );

        //return User::find($user->id);

        //raw delete in answer table for userid, testid
        //dd($user->id);
        DB::table('answers')->where([
            ['test_id', '=', $test_id],
            ['user_id', '=', $user->id],
        ])->delete();

        //is_submiited ->false

        //redirect to question
        return redirect('/test/'.$test_id.'/question');
    }
    public function create_question(){
        $data =[
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
            ['question'=>'kjahsjag','option1'=>'afb','option2'=>'afhsf','option3'=>'afbsdgg','option4'=>'fbhagg','marks'=>'1','answer'=>'fbhagg','test_id'=>'1'],
           
        ];
        foreach($data as $d)
        {
            Question::create($d);
        }
        return 'Question Created Successfully';
    }

    public function truncate_answer_table(){
        Answer::truncate();
        return "Answer table truncate success";

    }

}
