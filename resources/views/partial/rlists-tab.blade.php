<div class="row text-center">
    <div class="form-group col">
        <form enctype="multipart/form-data" action="/upload" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            r list: <input name="rlist" type="file">
            <input type="submit" value="Send File">
        </form>
    </div>
</div>

@if ( isset($rlists) )
    <div class="form-group">
        <label for="rlists-select">R Lists</label>
        <select class="form-control" id="rlists-select">
            @foreach ($rlists as $rl)
                <option value="{{ $rl }}">{{ date('Y-m-d', $rl) }}</option>
            @endforeach
        </select>
    </div>

@section('custom-js')
    @parent

    <script>
        $('#rlists-select').change(function () {
            $('#rlist-table').html('loading...');
            $('#results-table').html('loading...');
            $.post("getRList", {"_token": "{{ csrf_token() }}", rlist: this.value})
                .done(function (data) {
                    $('#rlist-table').html(data.listTable);
                    $('#results-table').html(data.resultsTable);
                });
        });

        $( document ).ready(function() {
            $('#rlists-select').trigger('change');
        });

    </script>
@endsection

@endif

<div class="row">

    <div class="col-6">
        <form action="/saveOrDownloadRList" method="POST">
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <input type="submit" name="download" class="btn btn-primary" value="Download">
            </div>
            <div id="rlist-table">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <input type="submit" name="download" class="btn btn-primary" value="Download">
            </div>
        </form>
    </div>

    <div class="col-1">
    </div>

    <div class="col-5">
        <div id="results-table">
        </div>
    </div>

</div>
