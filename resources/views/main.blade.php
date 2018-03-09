@extends('layouts.app')

@section('title', 'Prototype1')

@section('content')

    <ul class="nav nav-tabs" id="mainTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="results"
               aria-expanded="true">Results</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rlists-tab" data-toggle="tab" href="#rlists" role="tab" aria-controls="rlists">R Lists</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rresults-backups-tab" data-toggle="tab" href="#rresults-backups" role="tab" aria-controls="rresults-backups">Backups</a>
        </li>
    </ul>
    <div class="tab-content" id="mainTabContent">
        <div class="tab-pane fade show active" id="results" role="tabpanel" aria-labelledby="results-tab">

            @include('partial.results-tab')

        </div>
        <div class="tab-pane fade" id="rlists" role="tabpanel" aria-labelledby="rlists-tab">

            @include('partial.rlists-tab')

        </div>
        <div class="tab-pane fade" id="rresults-backups" role="tabpanel" aria-labelledby="rresults-backups">

            @include('partial.rresults-backups-tab')

        </div>
    </div>

@endsection

@section('custom-js')
    @parent

    @isset($names)
    <script>
        function GetHtml(player1, player2, result) {
            var $html = $('.extraMatchTemplate').clone();
            if (player1) {
                $html.find(".player1 option[value='" + player1 + "']").attr("selected", true);
            }
            if (player2) {
                $html.find(".player2 option[value='" + player2 + "']").attr("selected", true);
            }
            if (result) {
                $html.find(".result option[value='" + result + "']").attr("selected", true);
            }
            return $html.html();
        }

        $(document).ready(function () {


            @foreach ($results as $result)
            $('<div/>', {
                'class': 'extraMatch', html: GetHtml('{{$result['name1']}}', '{{$result['name2']}}', '{{$result['result']}}')
            }).prependTo('#extraMatchContainer');
            @endforeach


                window.extraMatchN = 1;
            $('#addGame').click(function () {
                window.extraMatchN++;
                $('<div/>', {
                    'class': 'extraMatch' + window.extraMatchN, html: GetHtml()
                }).prependTo('#extraMatchContainer');
                $(".extraMatch" + window.extraMatchN + " .select2").select2();

            });

            $('#addMatch').click(function () {
                $('#extraPackForm').show();
                $('#extraPackForm .select2').select2();
            });

            $('#packGenerate').click(function () {
                var n = $('#extraPackForm #packNumber').val();
                var pl1 = $('#extraPackForm #player1pack').val();
                var pl2 = $('#extraPackForm #player2pack').val();

                var change = false;
                var i;
                for(i=0; i<n; i++){
                    window.extraMatchN++;

                    if(!change){
                        $('<div/>', {
                            'class': 'extraMatch' + window.extraMatchN, html: GetHtml(pl1, pl2, '')
                        }).prependTo('#extraMatchContainer');

                    }else{
                        $('<div/>', {
                            'class': 'extraMatch' + window.extraMatchN, html: GetHtml(pl2, pl1, '')
                        }).prependTo('#extraMatchContainer');

                    }
                    change = !change;
                    $(".extraMatch" + window.extraMatchN + " .select2").select2();

                }
                $('#extraPackForm').hide();
            });

            $(".extraMatch .select2").select2();

            $("#extraMatchContainerLoading").hide();

        });
    </script>
    @endisset

@endsection

