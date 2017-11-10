<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use App\Data;
use App\Mail\AlertICEContacts;
use App\Client;



class DataController extends Controller
{
    private function generateResponse(string $status_txt, string $code, $body='') : array
    {
        $return['header']['status'] = $status_txt;
        $return['header']['code'] = $code;
        
        if($code == "200"){
            $return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
        }
        
        return $return;
    }//end of generateResponse
    //
    public function create(Request $request) 
    {
        $this->validate($request, [
           'message' => 'required|min:2',
           'lon' => 'required',
            'lat' => 'required',
            'id'=>'required'
        ]);
        $data = $request->all();
        //dd($data);
        $data['client_id'] = $data['id'];
        unset($data['id']);
        //dd($data);
        //add the data to the table and send a mail too
        try{
            $save_dt = Data::create($data);
        }catch(Exception $e){
            \Log::error($er);
            //post operation failed
            $return = $this->generateResponse("ERROR", "110", null);
            
            return response()->json($return);
        }
        
        //everything went well, send a a mail nau to notify the ICE email contacts.
        //get client data
        $client_data = Client::find($data['client_id']);
        Mail::to($client_data->rec_email_1)->send(new AlertICEContacts($client_data, $save_dt));
        Mail::to($client_data->rec_email_2)->send(new AlertICEContacts($client_data, $save_dt));
        Mail::to($client_data->rec_email_3)->send(new AlertICEContacts($client_data, $save_dt));
        
        //return success
        $return =  $this->generateResponse("DONE", "200", null);
        
        return response()->json($return);
        
    }//end of create()
}
