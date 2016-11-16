<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Addresses App</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
                font-size: 16px;
            }
            
            .list-group-item-heading{
                font-weight: bold;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {

            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }


            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 14px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .btn-new{
                width: 100%;
            }

            #new-address{
                display: none;
                padding: 10px;
            }
            #new-address label{
                float: left;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                </div>
            @endif

            <div class="content container">
                <div class="title m-b-md">
                    Address</br>
                    <span style="font-size: 20px;display: block">- search engine -</span>
                </div>

                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="form-group col-sm-3">
                        <input id="searchbox" type="text" class="form-control" />

                    </div>
                    <div class="col-sm-1">
                        <input type="button" class="btn btn-new btn-default" value="New" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <div id="loader">Loading...</div>
                        <div class="list-group" id="new-address">
                            <h2>New Address</h2>
                            <form class="address">
                                <div class="form-group">
                                    <label for="address_line_1">* Address line 1:</label>
                                    <input type="text" class="form-control" name="address_line_1" id="address_line_1" required>
                                </div>
                                <div class="form-group">
                                    <label for="address_line_2">Address line 2:</label>
                                    <input type="text" class="form-control" name="address_line_2" id="address_line_2">
                                </div>
                                <div class="form-group">
                                    <label for="city">* City:</label>
                                    <input type="text" class="form-control" name="city" id="city">
                                </div>
                                <div class="form-group">
                                    <label for="zip">* Postal code:</label>
                                    <input type="text" class="form-control" name="zip" id="zip">
                                </div>
                                <div class="form-group">
                                    <label for="country">* Country:</label>
                                    <select class="form-control" name="country" id="country">
                                        @include('partials/_countries')
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-create-address btn-default">Create Address</button>
                                <button type="submit" class="btn btn-cancel btn-default">Cancel</button>
                            </form>
                        </div>
                        <div class="list-group" id="addresses">
                        </div>
                    </div><!-- /.col-sm-4 -->
                    <div class="col-sm-4"></div>
                </div>

            </div>
        </div>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script type="text/javascript">
            function toggleLoader(show){
                $('#loader').toggle(show);
                $('#addresses').toggle(!show);
            }
            function getAddresses(){
                $.get('api/addresses', function(addresses){
                    appendAddresses(addresses);
                });
            }
            function appendAddresses(addresses){
                $('#addresses').html('');
                toggleLoader(false);
                for(var i in addresses){
                    appendAddress(addresses[i]);
                }
            }
            function appendAddress(address){
                var html =  '\
                <a href="#" class="list-group-item">\
                    <h4 class="list-group-item-heading">Address: ' + address.id + '</h4>\
                    <p class="list-group-item-text">' + address.address_line_1+ '</p>\
                    <p class="list-group-item-text">' + address.address_line_2+ '</p>\
                    <p class="list-group-item-text">' + address.city+ '</p>\
                    <p class="list-group-item-text">' + address.zip+ '</p>\
                    <p class="list-group-item-text">' + address.country+ '</p>\
                </a>';
                $('#addresses').append(html);
            }

            function showNewAddressForm(show){
                $('#addresses').toggle(!show);
                $('#new-address').toggle(show);
            }

            function formValid(){
                return $('#address_line_1').val() &&
                        $('#zip').val() &&
                        $('#city').val() &&
                        $('#country').val();
            }

            function createNewAddress(e){
                e.preventDefault();
                if(!formValid()){
                    alert('Please fill in all required fields');
                    return;
                }
                $.ajax({
                    url: 'api/address',
                    type: 'POST',
                    data: $('form.address').serialize(),
                    success: function(result) {
                        showNewAddressForm(false);
                        toggleLoader(true);

                        //Allow Elasticsearch to store changes
                        window.setTimeout(function(){
                            getAddresses();
                        }, 1000);
                    }
                });
            }

            function registerAutocomplete(){
                $( "#searchbox" ).autocomplete({
                    source: function( request, response ) {
                        toggleLoader(true);
                        $.ajax({
                            url: "api/addresses/" + request.term,
                            dataType: "jsonp",
                            success: function( data ) {
                                response( data );
                                appendAddresses(data);
                            },
                            complete: function(data){
                                appendAddresses(JSON.parse(data.responseText));
                            }
                        });
                    },
                    minLength: 0,
                    select: function( event, ui ) {
                        console.log('dd', ui.item);
                        log( ui.item ?
                        "Selected: " + ui.item.label :
                        "Nothing selected, input was " + this.value);
                    }
                    /*open: function() {
                        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
                    },
                    close: function() {
                        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                    }*/
                });
            }
            function registerEvents(){
                $('body')
                        .on('click', '.btn-new', function(){showNewAddressForm(true)})
                        .on('click', '.btn-cancel', function(){showNewAddressForm(false)})
                        .on('click', '.btn-create-address', createNewAddress);
            }
            (function(){
                getAddresses();
                registerAutocomplete();
                registerEvents();
            })();

        </script>
    </body>
</html>
