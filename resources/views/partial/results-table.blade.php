@if ( isset($results) )
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Name</th>
            <th>Result</th>
            <th>Delta</th>
        </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < count($results); $i++)
            <tr>
                <td title="{{$rByName[$results[$i]['name1']]}}">{{ $results[$i]['name1'] }}</td>
                <td title="{{$rByName[$results[$i]['name2']]}}">{{ $results[$i]['name2'] }}</td>
                <td>{{ $results[$i]['result'] }}</td>
                <td>{{ $rResDeltas[$i] }}</td>
            </tr>
        @endfor
        </tbody>
    </table>
@endif