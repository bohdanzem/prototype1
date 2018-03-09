<?php

namespace App\Http\Controllers;

use App\RList;
use Illuminate\Http\Request;

class RListController extends Controller {
    public function index() {
        $rList     = new RList();
        $rListData = $rList->parseTheLatestRList();

        if ( isset( $rListData['error_msg'] ) ) {
            return view( 'main', [ 'msg' => $rListData['error_msg'] ] );
        }

        $rResData = $rList->parseRRes( $rListData['parseRes']['rlist'] );

        //$rResDeltas = $rList->getRResDeltas( $rResData, $rListData['parseRes']['rByName'] );;

        $rresultsBackups = $rList->getRResultsBackups();

        return view( 'main', [
            'list'            => $rListData['parseRes']['list'],
            'names'           => $rListData['parseRes']['names'],
            'rlist'           => $rListData['parseRes']['rlist'],
            'rlists'          => $rListData['rlists'],
            'results'         => $rResData,
            /*'rResDeltas'      => $rResDeltas,*/
            /*'rByName'         => $rListData['parseRes']['rByName'],*/
            'rresultsBachups' => $rresultsBackups,
        ] );

    }

    public function upload( Request $request ) {
        if ( ! $request->hasFile( 'rlist' ) ) {
            return redirect( '/' )->with( 'msg', 'no file uploaded' );
        }
        $file = $request->file( 'rlist' );

        if ( ! $file->isValid() ) {
            return redirect( '/' )->with( 'msg', 'failed to upload the file' );
        }

        $fileName = '';
        $i        = 0;
        while ( ( $fileName === '' ) || ( file_exists( $fileName ) ) ) {
            $fileName = RList::getRListsPath() . '/' . (string) microtime( true );
            $i ++;
            if ( $i > 1000 ) {
                return redirect( '/' )->with( 'msg', 'can not find free name for upload' );
            }
        }
        $file->move( RList::getRListsPath(), $fileName );

        return redirect( '/' )->with( 'msg', 'uploaded successfully' );

    }

    public function saveOrDownloadRList( Request $request ) {
        $doDownload = $request->input( 'download' );
        $rlist       = $request->input( 'rlist' );
        if ($doDownload) {
            $pathToRList   = RList::getRListsPath().'/'.$rlist;
            $rListfileName = date('Y-m-d', $rlist).'-rlist.txt';

            return response()->download($pathToRList, $rListfileName);
        }

        $playerNames = $request->input( 'playerNames' );
        $playerRs    = $request->input( 'playerRs' );
        $playerKs    = $request->input( 'playerKs' );
        $playerNs    = $request->input( 'playerNs' );
        $rlist       = $request->input( 'rlist' );
        $newRText    = '';
        foreach ( $playerKs as $key => $val ) {
            $playerName = trim( $playerNames[ $key ] );
            $playerR    = trim( $playerRs[ $key ] );
            $playerK    = trim( $playerKs[ $key ] );
            $playerN    = trim( $playerNs[ $key ] );
            $newRText   .= $playerName . "\t" . $playerR . ' ' . $playerK . ' ' . $playerN . "\n";
        }
        $pathToNewRList = RList::getRListsPath() . '/' . $rlist;
        file_put_contents( $pathToNewRList, $newRText );

        return redirect( '/' )->with( 'msg', 'Ks saved' );
    }

    public function downloadNewRList( Request $request ) {
        $player1Ar = $request->input( 'player1' );
        $player2Ar = $request->input( 'player2' );
        $resultAr  = $request->input( 'result' );
        $rlist     = $request->input( 'rlist' );
        $withDeltasVal = $request->input( 'with_deltas' );
        $withDeltas = ($withDeltasVal == "1");

        if ( ! is_array($player1Ar)) {
            return redirect( '/' )->with( 'msg', 'no new results' );
        }

        $rList = new RList();
        $rList->saveResults( $player1Ar, $player2Ar, $resultAr, $rlist );

        list($newRList, $newNList, $parseRes) = $rList->clcNewRList( $player1Ar, $player2Ar, $resultAr, $rlist);
        $pathToNewRList = $rList->saveNewRList( $newRList, $newNList, $parseRes, $withDeltas );

        $newFileName = date( 'Y-m-d' ) . '-rlist-based-on-' . date( 'Y-m-d', $rlist ) . '.txt';

        return response()->download( $pathToNewRList, $newFileName )->deleteFileAfterSend( true );

    }

    public function exportResults( Request $request ) {
        $player1Ar = $request->input( 'player1' );
        $player2Ar = $request->input( 'player2' );
        $resultAr  = $request->input( 'result' );
        $rlist     = $request->input( 'rlist' );

        if ( ! is_array($player1Ar)) {
            return redirect( '/' )->with( 'msg', 'no new results' );
        }

        $rList = new RList();
        $rList->saveResults( $player1Ar, $player2Ar, $resultAr, $rlist );

        $resultsPath = RList::getRResPath() . '/' . $rlist . '-res';

        $newFileName = 'results-for-' . date( 'Y-m-d', $rlist ) . '.txt';

        return response()->download( $resultsPath, $newFileName );
    }

    public function importResults( Request $request ) {
        $rlist = $request->input( 'rlist' );

        if ( ! $request->hasFile( 'import_results' ) ) {
            return redirect( '/' )->with( 'msg', 'no file uploaded' );
        }
        $file = $request->file( 'import_results' );

        if ( ! $file->isValid() ) {
            return redirect( '/' )->with( 'msg', 'failed to upload the file' );
        }

        $rList    = new RList();
        $errorMsg = $rList->backupResultsFile( $rlist );
        if ( $errorMsg !== '' ) {
            return redirect( '/' )->with( 'msg', $errorMsg );
        }

        $file->move( RList::getRResPath(), $rlist . '-res' );

        return redirect( '/' )->with( 'msg', 'Results was successfully imported' );
    }

    public function saveResults( Request $request ) {
        $player1Ar = $request->input( 'player1' );
        $player2Ar = $request->input( 'player2' );
        $resultAr  = $request->input( 'result' );
        $rlist     = $request->input( 'rlist' );

        if ( ! is_array($player1Ar)) {
            return redirect( '/' )->with( 'msg', 'no new results' );
        }

        $rList = new RList();
        $rList->saveResults( $player1Ar, $player2Ar, $resultAr, $rlist );

        return redirect( '/' )->with( 'msg', 'Results saved' );
    }

    public function getRList( Request $request ) {
        $rlist = $request->input( 'rlist' );

        $rList     = new RList();
        $rListData = $rList->parse( $rlist );
        if ( ! $rListData ) {
            return [
                'error_msg' => 'can not parse the latest rlist',
            ];
        }

        $rResData = $rList->parseRRes( $rListData['rlist'] );

        $player1Ar = [];
        $player2Ar = [];
        $resultAr = [];
        foreach ($rResData as $rRes){
            $player1Ar[] = $rRes['name1'];
            $player2Ar[] = $rRes['name2'];
            $resultAr[] = $rRes['result'];
        }

        $rResDeltas = $rList->getRResDeltas( $rResData, $rListData['rByName'] );

        list($newRList, $newNList, $parseRes, $rcList, $rcNList, $scoreSumList) = $rList->clcNewRList( $player1Ar, $player2Ar, $resultAr, $rlist);

        return response()->json( [
            'listTable'    => view( 'partial.rlist-table', [
                'list'  => $rListData['list'],
                'rlist' => $rListData['rlist'],
                'newRList' => $newRList,
                'rcList' => $rcList,
                'rcNList' => $rcNList,
                'scoreSumList' => $scoreSumList,
            ] )->render(),
            'resultsTable' => view( 'partial.results-table', [
                'results'    => $rResData,
                'rResDeltas' => $rResDeltas,
                'rByName'    => $rListData['rByName'],
            ] )->render(),
        ] );
    }

    public function downloadResultsBackup( Request $request ) {
        $file = $request->input( 'file' );

        if ( ! preg_match( '/^bkfile-(\d+\.\d+)-res-bk-(\d+\.\d+)$/', $file, $matches ) ) {
            return redirect( '/' )->with( 'msg', 'can not download results backup' );
        }

        $rlist = $matches[1];
        $date  = $matches[2];

        $resultsBackupPath = RList::getRResBackupPath() . '/' . $rlist . '-res-bk-' . $date;

        $fileName = 'rlist-' . date( 'Y-m-d', $rlist ) . '-backup-date-' . date( 'Y-m-d', $date ) . '.txt';

        return response()->download( $resultsBackupPath, $fileName );
    }

    public function clearResultsBackup( Request $request ) {
        $rList     = new RList();
        $rListData = $rList->clearResultsBackups();

        return redirect( '/' )->with( 'msg', 'Backups cleared' );

    }

}
