@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Full Details of {{$details->last_name}}</div>

                <div class="panel-body">

                    <table class="table table-bordered" id="clients-table">
                        <tbody>
                            <tr>
                                <th>Personal Details</th>
                                <td>
                                    <strong>Full name: </strong> {{$details->last_name}} {{$details->other_names}} <br>
                                    <strong>Gender: </strong> {{$details->gender}} <br>
                                    <strong>Email addr.: </strong> {{$details->email}} <br>
                                    <strong>Phone No.: </strong> {{$details->phone}} <br>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <th>ICE Phone Nos.</th>
                                <td>
                                    <strong>Line 1: </strong>{{$details->ice_1}}<br>
                                    <strong>Line 2: </strong>{{$details->ice_2}}<br>
                                    <strong>Line 3: </strong>{{$details->ice_3}}<br>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <th>ICE Email Adds.</th>
                                <td>
                                    <strong>Address 1: </strong>{{$details->rec_email_1}}<br>
                                    <strong>Address 2: </strong>{{$details->rec_email_2}}<br>
                                    <strong>Address 3: </strong>{{$details->rec_email_3}}<br>
                                    
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <!-- to hold additional buttons -->
                    <div>
                        <a href="{{route('home')}}" class="btn btn-primary">Back</a> 
                        <a href="#" class="btn btn-danger"> Delete Client</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection