<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Datatables Styles -->
    <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    
    
    <div class="container">
        <h2>API Documentation</h2>
        <p>
            This is the documentation for the API for dreamsecure.
            Each Accordion below represents endpoints and clicking to expand shows more details about the endpoint.
            Details like the required fields, the JSON format for both request and the JSON format for response.
        </p>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/register
            </div>
            <div class="panel-body">
                This endpoint makes it possible for you to create a new dream secure app user.
                <code>
                {
                    "last_name": "Olakunle",
                    "other_names": "Odegbaro",
                    "email": "oodegbaro@gmail.com",
                    "phone": "09097694139",
                    "gender": "m",
                    "password": "killacam",
                    "ice_1": "090989784456",
                    "ice_2": "090989784466",
                    "ice_3": "090989784488",
                    "rec_email_1": "o.odegbaro@dreammesh.ng",
                    "rec_email_2": "o.odegbaro@gmail.com",
                    "rec_email_3": "sanya@gmail.com"

                }
                </code>
            </div>
        </div>
    </div>

<!-- Datatables Scripts -->
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    
    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}"></script> -->
</body>
</html> 