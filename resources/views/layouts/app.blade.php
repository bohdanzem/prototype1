<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('/css/libs/bootstrap.min.css?v4.0.0-beta') }}" rel="stylesheet">
    <link href="{{ asset('/css/libs/select2.min.css?4.0.3') }}" rel="stylesheet">

    <style>
        .extraMatchTemplate, #extraPackForm {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">

    @if (session('msg'))
        <div class="row">
            <div class="col text-center">
                {{ session('msg') }}
            </div>
        </div>
    @endif

    @if( ! empty($msg))
        <div class="row">
            <div class="col text-center">
                {{ $msg }}
            </div>
        </div>
    @endif

    @yield('content')

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ asset('/js/libs/jquery.min.js?3.2.1') }}"></script>
<script src="{{ asset('/js/libs/popper.min.js?1.11.0') }}"></script>
<script src="{{ asset('/js/libs/bootstrap.min.js?4.0.0-beta') }}"></script>
<script src="{{ asset('/js/libs/select2.full.min.js?4.0.3') }}"></script>

@section('custom-js')
    <script>
        /*$.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });*/
    </script>
@show
</body>
</html>



