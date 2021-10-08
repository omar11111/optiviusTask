<?php

namespace App\Http\Controllers;

use App\Mail\sendMail;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Spatie\Translatable\HasTranslations;
class AuthController extends Controller
{
    use ApiResponse;
    use HasTranslations;
    public $translatable = ['name'];
    private  function registerValidate($request){
        $rules =[
            'name_en'  => 'required|string',
            'name_ar'  => 'required|string',
            'phone' => 'required|regex:/^01[0-2,5]{1}[0-9]{8}/|unique:users,phone',
            'email' => 'required|email|unique:users,email',
           
            'password'=>'required|confirmed', 
        ];
        $message = [
            "name.required" => "name must  have value",
        ];
        $validator= Validator::make($request->all(),$rules, $message );
       
        return $validator;
 
    }

 

    private function codeValidate($request)
    {
        $rules =['code'=>'required|numeric'];
        $validator= Validator::make($request->all(),$rules);
        
        return $validator;
    }

    private function checkAndHash($user,$request)
    {
        if (!is_null($user->email_verified_at)) {

            if (Hash::check( $request , $user->password) ) {

                return $this->apiResponse(1,'user logined',[
                    'api_token'=> $user->api_token,
                    'client'=>$user
                ]);
            }else {
                
                
                return $this->apiResponse(0,'the data you have enterd is not valid', [] );

            }

        }else {

            return $this->apiResponse(0,'please verify your account if not verified the data you have enterd is not valid ',[]);

        }
    }


    public function register(Request $request)
    {
        
        $validator=$this->registerValidate($request);
        if ($validator->fails()) {
          return  $this->apiResponse(0,$validator->errors()->first(),$validator->errors());
        }

       $request->merge(['password'=> Hash::make($request->password)]);
       $user =new User($request->all());
       
       $user->name=json_encode(['en'=>$request->name_en,'ar'=>$request->name_ar]);
       $user->api_token=Str::random(60);
       $user->code=rand(10000,99999);
     
       $user->save();
       Mail::to($user->email)->send(new sendMail($user->code));
       return $this->apiResponse(1, 'user added succefully',
       ['api_token'=> $user->api_token,
       'client'=>$user]);
      
    }


    public function verifyEmail(Request $request ,$id)
    {

        $validator= $this->codeValidate($request);
        if ($validator->fails()) {
            return  $this->apiResponse(0,$validator->errors()->first(),$validator->errors());
          }
        $user = User::findOrFail($id);
        if ($user) {
            if ($user->code == $request->code) {
                $user->email_verified_at = now();
                $user->save();
                return $this->apiResponse(1, 'Email verified at '.now(),
                ['api_token'=> $user->api_token,
                'client'=>$user]);
             }else{
                return $this->apiResponse(0, 'please check your code ',
                []);
             }
        }
      

    }
    

    public function loginPage(Request $request)
    {
    
        if ( preg_match('/^01[0-2,5]{1}[0-9]{8}/', $request->loginfiled)) {

            $user =User::where('phone',$request->loginfiled)->first();
            if ($user === null) {
                return $this->apiResponse(1, 'check your data Or Register',
                []);
              } else {
                return $this->checkAndHash($user,$request->password);
              }
          

        }elseif (filter_var($request->loginfiled,FILTER_VALIDATE_EMAIL)) {
          
            $user =User::where('email',$request->loginfiled)->first();
            if ($user === null) {
                return $this->apiResponse(1, 'check your data Or Register',
                []);
              } else {
                return $this->checkAndHash($user,$request->password);
              }
           
        }
    }


    public function profile(Request $request)
    {


    //    variable to know the language
        $lang=$request->lang;
        // get user id of auth user
        $userId=$request->user()->id;
        // get alll user data with the articles he created
        $user = User::with('articles')->find($userId);
        //encode name
        $user->name=json_decode($user->name);
        // dd($user);

        // get the user name that fit language
        foreach ($user->name as $key => $value) {
            if ($key==$lang) {
                $user->name=$value;
            }
        }

//  // get the articles title and content that fit language
         foreach ($user->articles as $key => $article) {
        $article->title=json_decode($article->title);
        $article->content=json_decode($article->content);
        
        foreach ($article->title as $key2 => $title) {
            if ($key2==$lang) {
                $article->title=$title;
            }
        }

        foreach ($article->content as $key2 => $value) {
            if ($key2==$lang) {
                $article->content=$value;
            }
        }
     }  
     
     


        
     
        
        return  $this->apiResponse(1,'user profile data',$user);
    }
}


