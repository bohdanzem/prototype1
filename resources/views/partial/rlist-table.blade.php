@if ( isset($list) )
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input name="rlist" type="hidden" value="{{ $rlist }}">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>R</th>
            <th>K</th>
            <th>N</th>
            <th>dR</th>
            <th>dN</th>
            <th>Ra</th>
            <th>Score</th>
        </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < count($list); $i++)
            <tr>
                <th scope="row">{{ $i + 1 }}</th>
                <td><input name="playerNames[]" type="hidden" value="{{ $list[$i]['name'] }}">{{ $list[$i]['name'] }}</td>
                <td><input name="playerRs[]" type="hidden" value="{{ $list[$i]['r'] }}">{{ $list[$i]['r'] }}</td>
                <td><input name="playerKs[]" style="max-width: 40px" type="text" value="{{ $list[$i]['k'] }}"></td>
                <td><input name="playerNs[]" type="hidden" value="{{ $list[$i]['n'] }}">{{ $list[$i]['n'] }}</td>
                <td>{{ $newRList ? (isset($newRList[$list[$i]['name']])?(($newRList[$list[$i]['name']]-$list[$i]['r'])):('--')):'-' }}</td>
                <td>{{ $rcNList ? ((isset($rcNList[$list[$i]['name']]))?($rcNList[$list[$i]['name']]):('--')) : '-' }}</td>
                <td>{{ $rcList ? ((isset($rcList[$list[$i]['name']]))?(round($rcList[$list[$i]['name']], 2)):('--')) : '-' }}</td>
                <td>{{ $scoreSumList ? ((isset($scoreSumList[$list[$i]['name']]))?($scoreSumList[$list[$i]['name']]):('--')) : '-' }}</td>
            </tr>
        @endfor
        </tbody>
    </table>

@endif