<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationCode;
use App\Mail\WelcomeMail;
use App\Mail\NotifyICEContacts;
class ClientController extends Controller
{
    
    private function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }
    
    private function getToken($length)
    {
         $token = "";
         $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
         $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
         $codeAlphabet.= "0123456789";
         $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        return $token;
    }
    
    private function checkEmailAccount(string $email): bool
    {
        //this is to check if the user email already exists it returns 
        //true if exists and false otherwise
        
        $emails = Client::pluck('email')->toArray();
        //dd($emails);
        return (in_array($email, $emails));
        
    }
    
    private function generateResponse($status_txt, $code, $body='')
    {
        $return['header']['status'] = $status_txt;
        $return['header']['code'] = $code;
        
        if($code == "200"){
            $return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
        }
        
        return $return;
    }//end of generateResponse

    public function registerClient(Request $request)
    {
        
        
        
        $user_data = $request->input('user');
        $user_data = collect($user_data);
        //$test_data = collect(['name' => 'Olakunle', 'age' => 37, 'gender' => 'Male']);
        //dd($test_data['name']);
        
        /*$this->validate($request, [
            'user.last_name' => 'required|min:2|max:100',
            'other_names' => 'required|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required',
            'password' => 'required',
            
            'ice_1' => 'required',
            'ice_2' => 'required',
            'ice_3' => 'required',
            
            'rec_email_1' => 'required|email',
            'rec_email_2' => 'required|email',
            'rec_email_3' => 'required|email'
        ]);*/
        
        //hash the password
        //$request->user['password'] = Hash::make($request['password']);
        $user_data['password'] = Hash::make($request->input('user.password'));
        //generate the unique code
        $code = $this->getToken(5);
        
        //add it to $request object
        //$request->request->user->add(['code'=> $code]);
        $user_data['code'] = $code;
        
        //dd($request->all());
        //dd($user_data);
        
        //check the email if it already exists
        $res = $this->checkEmailAccount($request->user['email']);
        //dd($res);
        if( $res == false ){
            //go ahead and create the client
            try{
                //$client = Client::create($request->all());
                $client = Client::create($user_data);
            }catch(Exception $e){
                //Log the messgae if any error
                //$return['header']['status'] = "ERROR";
				//$return['header']['code'] = "110";
                $return = $this->generateResponse("ERROR", "110", null);
                
                return response()->json($return);
                //return $e;
            }
            //also send a mail to the person
            
            //return the users id
            //return $client->id;
            
            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['id'] = $client->id;
            
            Mail::to('oodegbaro@gmail.com')->send(new ActivationCode($client));
            
            return response()->json($return);
            
        }else{
            //return the checkEmailAccount Value / Response
            //$data = Client::where('email', $request['email'])->first();
            $data = Client::where('email', $user_data['email'])->first();
            //return $data->id;
            
            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['id'] = $data->id;
            
            return response()->json($return);
        }
    }//end of registerClient
    
    public function activateClient(Request $request)
    {
        $user_data = $request->input('user');
        /*$this->validate($request->user, [
            'code' => 'required'
        ]);*/
        
        //select users to see if they exist
        //$res = Client::where('code', $request->input('code'))->first();
        $res = Client::where('code', $user_data['code'])->first();
        if($res ==  null){
            //no user found return null
            $return = $this->generateResponse("ERROR", "200", null);
            
            return response()->json($return);
        }
        
        if($res->activated != 1){
            //user is not yet activated
            //update activated and set to 1
            //$res->activated = 1;
            //$res->save();
            
            try{
                $res->update(['activated' => 1]);
                
                //send a mail to show all your data and welcome the user on board
                Mail::to($res->email)->send(new WelcomeMail($res));
                
                //notify the contacts as using them for emergency contact
                Mail::to($res->rec_email_1)->send(new NotifyICEContacts($res));
                Mail::to($res->rec_email_2)->send(new NotifyICEContacts($res));
                Mail::to($res->rec_email_3)->send(new NotifyICEContacts($res));
                
                
                $return = $this->generateResponse("DONE", "200", null);
                $return['body']['userData'] = $res->toArray();
                
                return response()->json($return);
            }catch(Exception $e){
                \Log::error($e);
                $return = $this->generateResponse("ERROR", "107", null);
            
                return response()->json($return);
            }
        }else{
            //user is already activated, return success and user data
            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['userData'] = $res->toArray();
            
            return response()->json($return);
        }
        
        
    }//end of activateClient
    
    public function loginClient(Request $request)
    {
        $usr_data = $request->input('user');
        /*$this->validate($request->user, [
            'email' => 'required|email',
            'password' => 'required'
        ]);*/  
        try{
            //$exist = Client::where('email', $request->input('email'))->first();
            $exist = Client::where('email', $user_data['email'])->first();
        }catch(Exception $e){
            \Log::error($e);
        }
        if($exist == null){
            //the user doesn't exist
            $return = $this->generateResponse("ERROR", "101", null);
            
            return response()->json($return);
        }
        
        if($exist->activated == 0){            
            $return = $this->generateResponse("ERROR", "111", null);
            
            return response()->json($return);
        }
        //check to see if password matches
        if(Hash::check($user_data['password'], $exist->password)){
            //user exist and gave valid credentials 
            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['userData'] = $exist->toArray();
            
            return response()->json($return);
        }else{
            $return = $this->generateResponse("ERROR", "107", null);
            
            return response()->json($return);
        }
    }//end of loginClient
    
    public function getClientDetails(Request $request)
    {
        $user_data = $request->input('user');
        //dd($request->user);
        /*
        dd($request->user);
        array:10 [
          "id" => "2"
          "last_name" => null
          "other_names" => null
          "email" => null
          "phone" => null
          "ice_1" => null
          "ice_2" => null
          "ice_3" => null
          "rec_email_1" => null
          "rec_email_2" => null
        ]
        */
        /*$this->validate($request->user, [
            'id' => 'required'
        ]);*/
        try{
            $data = Client::find($user_data['id']);
        }catch(Exception $e){
            \Log::error($e);
            
            $return = $this->generateResponse("ERROR", "106", null);
            
            return response()->json($return);
        }
        
        if($data == null){
            
            $return = $this->generateResponse("ERROR", "110", null);
            
            return response()->json($return);
        }
        
        
        $return = $this->generateResponse('DONE', '200', null);
        $return['body']['userData'] = $data->toArray();
        
        return response()->json($return);
        
    }//end of getClientDetails
    
    public function updateClientDetails(Request $request)
    {
        
        $user_data = $request->input('user');
        
        /*$this->validate($request, [
            'id' => 'required',
            'last_name' => 'required|min:2|max:100',
            'other_names' => 'required|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required',
            'password' => 'required',
            
            'ice_1' => 'required',
            'ice_2' => 'required',
            'ice_3' => 'required',
            
            'rec_email_1' => 'required|email',
            'rec_email_2' => 'required|email',
            'rec_email_3' => 'required|email'
        ]);*/
        
        
        //$user = $request->input('user');
        //get the client
        try{
            $client = Client::findOrFail($user_data['id']);
            $client->fill($user_data)->save();
        }catch(Exception $e){
            \Log::error($e);
            $return = $this->generateResponse("ERROR", "107", null);
            
            return response()->json($return);
        }
        
        //it was successful
        $client = Client::findOrFail($user_data['id']);
        $return = $this->generateResponse("DONE", "200", null);
        $return['body'] = $client->toArray();
        
        return response()->json($return);
        
    }//end of updateClientDetails
    
    public function testMail()
    {
        /*Mail::send(['text' => 'mails'], ['name', 'Kunle'], function($message){
            $message->to('oodegbaro@gmail.com', 'To Basketball')->subject('Test Email');
            $message->from('oodegbaro@gmail.com', 'ODEGBARO');
        });*/
        Mail::to('oodegbaro@gmail.com')->send(new ActivationCode());
    }
}
