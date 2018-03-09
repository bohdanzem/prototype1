@isset($names)

    <div id="extraPackForm">
        <div class="form-row justify-content-md-center">
            <div class="form-group col-md-3">
                <select class="select2 player1 form-control select2" name="player1pack" id="player1pack">
                    <option value="-">-</option>
                    @foreach ($names as $name)
                        <option value="{{$name}}">{{$name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <select class="select2 player2 form-control select2" name="player2pack" id="player2pack">
                    <option value="-">-</option>
                    @foreach ($names as $name)
                        <option value="{{$name}}">{{$name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-1">
                <input class="result form-control" name="number" id="packNumber">
            </div>
        </div>
        <div class="form-row justify-content-md-center">
            <div class="form-group col-md-7 text-center">
                <button id="packGenerate">Generate</button>
            </div>
        </div>
    </div>

<div class="extraMatchTemplate">
    <div class="form-row justify-content-md-center">
        <div class="form-group col-md-3">
            <select class="player1 form-control select2" name="player1[]">
                <option value="-">-</option>
                @foreach ($names as $name)
                    <option value="{{$name}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <select class="player2 form-control select2" name="player2[]">
                <option value="-">-</option>
                @foreach ($names as $name)
                    <option value="{{$name}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-1">
            <select class="result form-control" name="result[]">
                <option value="-">-</option>
                <option value="1-0">1-0</option>
                <option value="0.5">0.5</option>
                <option value="0-1">0-1</option>
            </select>
        </div>
    </div>
</div>

<form enctype="multipart/form-data" action="/saveResults" method="POST" id="save-results-form">
    <input id="import-results" name="import_results" type="file" style="display: none;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="rlist" value="{{ $rlist }}">
    <input type="hidden" name="extra-action" value="save">
    <input type="hidden" name="with_deltas" id="downloadNewRListWDHidden" value="">

    <div class="row">
        <div class="col">
            <a href="#" id="addGame"><i class="icon-plus-sign icon-white"></i> Add Game </a> | 
            <a href="#" id="addMatch"><i class="icon-plus-sign icon-white"></i> Add Match </a>
        </div>
        <div class="col text-right">

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" id="saveResults">Save</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="downloadNewRList">Download new r list</a>
                    <a class="dropdown-item" href="#" id="downloadNewRListWD">Download new r list (with deltas)</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="exportResults">Export results</a>
                    <a class="dropdown-item" href="#" id="importResults">Import results</a>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <div id="extraMatchContainerLoading">Loading...</div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="extraMatchContainer"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="extraPackContainer"></div>
        </div>
    </div>
</form>

@section('custom-js')
    @parent
    <script>
        $('#save-matches').click(function () {
            $('#save-results-form').submit();
        });
        var extraActions = ['saveResults', 'downloadNewRList', 'exportResults'];

        for (var i = 0; i < extraActions.length; i++) {
            (function (i) {
                $('#' + extraActions[i]).click(function () {
                    $('#save-results-form').attr('action', extraActions[i]);
                    $('#save-results-form').submit();
                });
            })(i);
        }

        $('#downloadNewRListWD').click(function () {
            $('#downloadNewRListWDHidden').val('1');
            $('#save-results-form').attr('action', 'downloadNewRList');
            $('#save-results-form').submit();
        });

        $('#importResults').click(function () {
            $('#import-results').click();

        });
        $('#import-results').change(function () {
            $('#save-results-form').attr('action', "importResults");
            $('#save-results-form').submit();
        });

        $('#packButton').click(function () {


        });

    </script>


@endsection

@endisset
