<div class="row">
    <div class="col text-center">
        RLists:
    </div>
</div>

@if ( !empty($rresultsBackups) )
    <div class="row">
        <div class="col text-center">
            <ul style="list-style-type: none;">
                @foreach ($rresultsBackups as $bk)
                    <li>
                        rlist: {{ date('Y-m-d', $bk['rlist']) }}, bk date: {{ date('Y-m-d', $bk['date']) }}
                        <a href="#" class='download-rresults-backup' id="bkfile-{{$bk['file']}}">download</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col text-center">
            <form method="post" action="clearResultsBackup">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit">Clear All</button>
            </form>

        </div>
    </div>

    <form method="post" action="downloadResultsBackup" id="results-backup-form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="file" value="">
    </form>

@section('custom-js')
    @parent

    <script>
        $('.download-rresults-backup').click(function (event) {
            event.preventDefault();
            $("#results-backup-form input[name='file']").val($(this).attr('id'));
            $("#results-backup-form").submit();
        });

    </script>
@endsection


@else
    <div class="row">
        <div class="col text-center">
            no backups found
        </div>
    </div>
@endif

