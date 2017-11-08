<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationCode;
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
    
    public function checkEmailAccount(string $email): bool
    {
        //this is to check if the user email already exists it returns 
        //true if exists and false otherwise
        
        $emails = Client::pluck('email')->toArray();
        //dd($emails);
        return (in_array($email, $emails));
        
    }

    public function registerClient(Request $request)
    {
        $this->validate($request, [
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
        ]);
        
        //hash the password
        $request['password'] = Hash::make($request['password']);
        //generate the unique code
        $code = $this->getToken(5);
        
        //add it to $request object
        $request->request->add(['code'=> $code]);
        
        //dd($request->all());
        
        //check the email if it already exists
        $res = $this->checkEmailAccount($request['email']);
        //dd($res);
        if( $res == false ){
            //go ahead and create the client
            try{
                $client = Client::create($request->all());
            }catch(Exception $e){
                //Log the messgae if any error
                $return['header']['status'] = "ERROR";
				$return['header']['code'] = "110";
                
                return response()->json($return);
                //return $e;
            }
            //also send a mail to the person
            
            //return the users id
            //return $client->id;
            
            $return['header']['status'] = 'DONE';
            $return['header']['code'] = '200';
            $return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
            $return['body']['id'] = $client->id;
            
            Mail::to('oodegbaro@gmail.com')->send(new ActivationCode($client));
            
            return response()->json($return);
            
        }else{
            //return the checkEmailAccount Value / Response
            $data = Client::where('email', $request['email'])->first();
            //return $data->id;
            
            $return['header']['status'] = 'DONE';
            $return['header']['code'] = '200';
            $return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
            $return['body']['id'] = $data->id;
            
            return response()->json($return);
        }
    }//end of registerClient
    
    public function testMail()
    {
        /*Mail::send(['text' => 'mails'], ['name', 'Kunle'], function($message){
            $message->to('oodegbaro@gmail.com', 'To Basketball')->subject('Test Email');
            $message->from('oodegbaro@gmail.com', 'ODEGBARO');
        });*/
        Mail::to('oodegbaro@gmail.com')->send(new ActivationCode());
    }
}
