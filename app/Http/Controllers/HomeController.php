<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Client;


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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    /**
    * this processes datatables ajax request
    * @return \Illuminate\Http\JsonResponse
    */
    public function anyData()
    {
        //this is to return the data
        $clients = Client::select(['id', 'last_name', 'other_names', 'gender', 'email', 'phone', 'created_at']);
        return Datatables::of($clients)
            ->addColumn('full_name', function($client){
                return $client->last_name . ' ' . $client->other_names;
            })
            ->addColumn('action', function($client){
                return '<a href="'.route('admin.get.clientDetails',['client_id' => $client->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye"></i>View</a>';
            })
            ->editColumn('gender', function($client){
                if($client->gender == 'm'){
                    return 'Male';
                }else{
                    return 'Female';
                }
            })
            ->editColumn('created_at', function($client){
                return date('M jS, Y', strtotime($client->created_at));
            })
            ->removeColumn('last_name')
            ->removeColumn('other_names')
            //->orderColumn('email', 'email $1')
            ->make(true);
        //return Datatables::of(Client::query())->make(true);
    }
    
    /**
    * This function returns the full details of the given dream secure app user id
    */
    public function getClientDetails(Request $request, $client_id)
    {
        $details = Client::findOrFail($client_id);
        return view('client_view', compact('details'));
    }//end of getClientDetails
}
