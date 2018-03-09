<?php

namespace App;


class RList {
    const R_LIST_DIR = 'rlists';
    const R_RES_DIR = 'rresults';
    const TMP_DIR = 'tmp';
    const R_RES_BK_DIR = 'rresults_backup';
    protected $rListList = [];
    protected $rListNames = [];
    protected $rList = '';
    protected $rListRByName = [];
    protected $rListKByName = [];
    protected $rListNByName = [];

    public function __construct() {

    }

    public function getRResDeltas( $rResData, $rByName ) {
        $rResDeltas = [];
        foreach ($rResData as $key => $rRes){
            $r1 = $rByName[$rRes['name1']];
            $r2 = $rByName[$rRes['name2']];
            $score = ( $rRes['result'] === '1-0' ) ? ( 1 ) : ( ( $rRes['result'] === '0.5' ) ? ( 0.5 ) : ( 0 ) );
            if($r1 && $r2){
                $rResDeltas[$key] = $this->getDeltaR($r1, $r2, $score);
            }else{
                $rResDeltas[$key] = '-';
            }
        }
        return $rResDeltas;
    }

    public function parseRRes( $rList ) {
        $filePath = self::getRResPath() . '/' . $rList . '-res';
        if ( ! file_exists( $filePath ) ) {
            return [];
        }
        $rResLines = file( $filePath );
        $res       = [];
        foreach ( $rResLines as $line ) {
            if ( ! preg_match( "/(.+)\t(.+)\t(.+)/", $line, $matches ) ) {
                continue;
            }
            $name1  = trim( $matches[1] );
            $name2  = trim( $matches[2] );
            $result = trim( $matches[3] );

            if ( $name1 && $name2 && $result ) {
                $res[] = [
                    'name1'  => $name1,
                    'name2'  => $name2,
                    'result' => $result,
                ];
            }
        }
        $res = array_reverse( $res );

        return $res;
    }

    public function parse( $rList ) {
        $this->rList = $rList;
        $filePath    = self::getRListsPath() . '/' . $rList;
        if ( ! file_exists( $filePath ) ) {
            return false;
        }
        $rListLines         = file( $filePath );
        $this->rListRByName = [];
        foreach ( $rListLines as $line ) {
            if ( preg_match( '/(.+)\s+([0-9]+)\s+([0-9]+)\s+([0-9]+)/', $line, $matches ) ) {
                $name = trim( $matches[1] );
                $r    = trim( $matches[2] );
                $k    = trim( $matches[3] );
                $n    = trim( $matches[4] );

                $this->rListNames[] = $name;

                $this->rListRByName[ $name ] = $r;
                $this->rListKByName[ $name ] = $k;
                $this->rListNByName[ $name ] = $n;

                $this->rListList[] = [
                    'name' => $name,
                    'r'    => $r,
                    'k'    => $k,
                    'n'    => $n,
                ];
            }
        }

        return $this->getRListData();
    }

    public function getRListData() {
        return [
            'list'    => $this->rListList,
            'names'   => $this->rListNames,
            'rlist'   => $this->rList,
            'rByName' => $this->rListRByName,
            'kByName' => $this->rListKByName,
            'nByName' => $this->rListNByName,
        ];
    }

    public function parseTheLatestRList() {
        $d     = dir( self::getRListsPath() );
        $files = [];
        while ( false !== ( $entry = $d->read() ) ) {
            if ( preg_match( '/^\d+\.\d+$/', $entry ) ) {
                $files[] = $entry;
            }
        }
        if ( ! $files ) {
            return [
                'error_msg' => 'no rlists',
            ];
        }

        rsort( $files );

        $n   = 0;
        $res = false;
        while ( $res === false ) {
            $res = $this->parse( $files[ $n ] );
            if ( count( $files ) < $n + 1 ) {
                return [
                    'error_msg' => 'can not parse the latest rlist',
                ];
            }
            $n ++;
        }
        if ( ! $res ) {
            return [
                'error_msg' => 'can not parse the latest rlist',
            ];
        }

        return [
            'parseRes' => $res,
            'rlists'   => $files,
        ];
    }

    public static function getRListsPath() {
        return storage_path() . '/' . self::R_LIST_DIR;
    }

    public static function getRResPath() {
        return storage_path() . '/' . self::R_RES_DIR;
    }

    public static function getRResBackupPath() {
        return storage_path() . '/' . self::R_RES_BK_DIR;
    }

    public static function getTmpPath() {
        return storage_path() . '/' . self::TMP_DIR;
    }

    public function getDeltaR( $player1R, $player2R, $scoreOfPlayer1 ) {
        $dR = abs( $player1R - $player2R );
        $dP = $this->getDPFromTable81b( $dR );
        if ( $player1R < $player2R ) {
            $dP = 1 - $dP;
        }

        return ( $scoreOfPlayer1 - $dP );
    }

    public function getDRFromTable81a($dp)
    {

        $dRAr = [
            800,
            677,
            589,
            538,
            501,
            470,
            444,
            422,
            401,
            383,
            366,
            351,
            336,
            322,
            309,
            296,
            284,
            273,
            262,
            251,
            240,
            230,
            220,
            211,
            202,
            193,
            184,
            175,
            166,
            158,
            149,
            141,
            133,
            125,
            117,
            110,
            102,
            95,
            87,
            80,
            72,
            65,
            57,
            50,
            43,
            36,
            29,
            21,
            14,
            7,
            0,
            -7,
            -14,
            -21,
            -29,
            -36,
            -43,
            -50,
            -57,
            -65,
            -72,
            -80,
            -87,
            -95,
            -102,
            -110,
            -117,
            -125,
            -133,
            -141,
            -149,
            -158,
            -166,
            -175,
            -184,
            -193,
            -202,
            -211,
            -220,
            -230,
            -240,
            -251,
            -262,
            -273,
            -284,
            -296,
            -309,
            -322,
            -336,
            -351,
            -366,
            -383,
            -401,
            -422,
            -444,
            -470,
            -501,
            -538,
            -589,
            -677,
            -800,
        ];

        $dpTest = 1;
        $i      = 0;
        while ($dpTest >= 0) {
            if ($dp > $dpTest) {
                return $dRAr[$i];
            }
            $i++;
            $dpTest -= 0.01;
        }
        return -800;
    }

    public function getDPFromTable81b( $dR ) {
        if ( $dR <= 3 ) {
            return 0.5;
        }
        if ( $dR <= 10 ) {
            return 0.51;
        }
        if ( $dR <= 17 ) {
            return 0.52;
        }
        if ( $dR <= 25 ) {
            return 0.53;
        }
        if ( $dR <= 32 ) {
            return 0.54;
        }
        if ( $dR <= 39 ) {
            return 0.55;
        }
        if ( $dR <= 46 ) {
            return 0.56;
        }
        if ( $dR <= 53 ) {
            return 0.57;
        }
        if ( $dR <= 61 ) {
            return 0.58;
        }
        if ( $dR <= 68 ) {
            return 0.59;
        }
        if ( $dR <= 76 ) {
            return 0.60;
        }
        if ( $dR <= 83 ) {
            return 0.61;
        }
        if ( $dR <= 91 ) {
            return 0.62;
        }
        if ( $dR <= 98 ) {
            return 0.63;
        }
        if ( $dR <= 106 ) {
            return 0.64;
        }
        if ( $dR <= 113 ) {
            return 0.65;
        }
        if ( $dR <= 121 ) {
            return 0.66;
        }
        if ( $dR <= 129 ) {
            return 0.67;
        }
        if ( $dR <= 137 ) {
            return 0.68;
        }
        if ( $dR <= 145 ) {
            return 0.69;
        }
        if ( $dR <= 153 ) {
            return 0.70;
        }
        if ( $dR <= 162 ) {
            return 0.71;
        }
        if ( $dR <= 170 ) {
            return 0.72;
        }
        if ( $dR <= 179 ) {
            return 0.73;
        }
        if ( $dR <= 188 ) {
            return 0.74;
        }
        if ( $dR <= 197 ) {
            return 0.75;
        }
        if ( $dR <= 206 ) {
            return 0.76;
        }
        if ( $dR <= 215 ) {
            return 0.77;
        }
        if ( $dR <= 225 ) {
            return 0.78;
        }
        if ( $dR <= 235 ) {
            return 0.79;
        }
        if ( $dR <= 245 ) {
            return 0.80;
        }
        if ( $dR <= 256 ) {
            return 0.81;
        }
        if ( $dR <= 267 ) {
            return 0.82;
        }
        if ( $dR <= 278 ) {
            return 0.83;
        }
        if ( $dR <= 290 ) {
            return 0.84;
        }
        if ( $dR <= 302 ) {
            return 0.85;
        }
        if ( $dR <= 315 ) {
            return 0.86;
        }
        if ( $dR <= 328 ) {
            return 0.87;
        }
        if ( $dR <= 344 ) {
            return 0.88;
        }
        if ( $dR <= 357 ) {
            return 0.89;
        }
        if ( $dR <= 374 ) {
            return 0.90;
        }
        if ( $dR <= 391 ) {
            return 0.91;
        }
        if ( $dR <= 411 ) {
            return 0.92;
        }
        if ( $dR <= 432 ) {
            return 0.93;
        }
        if ( $dR <= 456 ) {
            return 0.94;
        }
        if ( $dR <= 484 ) {
            return 0.95;
        }
        if ( $dR <= 517 ) {
            return 0.96;
        }
        if ( $dR <= 559 ) {
            return 0.97;
        }
        if ( $dR <= 619 ) {
            return 0.98;
        }
        if ( $dR <= 735 ) {
            return 0.99;
        }

        return 1;


    }

    public function saveResults( $player1Ar, $player2Ar, $resultAr, $rlist ) {
        $resText = '';
        foreach ( $player1Ar as $i => $val ) {
            $player1 = trim( $player1Ar[ $i ] );
            $player2 = trim( $player2Ar[ $i ] );
            $result  = trim( $resultAr[ $i ] );
            if ( ( $player1 === '-' ) || ( $player2 === '-' ) || ( $result === '-' ) ) {
                continue;
            }
            $resText .= $player1 . "\t" . $player2 . "\t" . $result . "\n";
        }
        file_put_contents( self::getRResPath() . '/' . $rlist . '-res', $resText );
    }

    public function clcNewRList( $player1Ar, $player2Ar, $resultAr, $rlist ) {
        $resText             = '';
        $resultsFileText     = '';
        $allMatchesByPlayers = [];
        for ( $i = count( $player1Ar ) - 1; $i >= 0; $i -- ) {
            $player1 = trim( $player1Ar[ $i ] );
            $player2 = trim( $player2Ar[ $i ] );
            $result  = trim( $resultAr[ $i ] );
            if ( ( $player1 === '-' ) || ( $player2 === '-' ) || ( $result === '-' ) ) {
                continue;
            }
            $resText .= $player1 . "\n";
            $resText .= $player2 . "\n";
            $resText .= $result . "\n";

            $resultsFileText .= $player1 . "\t" . $player2 . "\t" . $result . "\n";

            $score = ( $result === '1-0' ) ? ( 1 ) : ( ( $result === '0.5' ) ? ( 0.5 ) : ( 0 ) );

            $allMatchesByPlayers[ $player1 ][] = [ 'oponent' => $player2, 'score' => $score];
            $allMatchesByPlayers[ $player2 ][] = [ 'oponent' => $player1, 'score' => 1 - $score ];
        }

        $rList    = new RList();
        $parseRes = $this->parse( $rlist );

        $newRList = [];
        $newNList = [];
        $rcList = [];
        $scoreSumList = [];
        $rcNList = [];

        foreach($parseRes['rByName'] as $player => $r){
            $newRList[$player] = $r;
        }

        foreach ( $allMatchesByPlayers as $player => $allMatches ) {
            $newNList[ $player ] = $parseRes['nByName'][ $player ];
            $newRList[ $player ] = 0;
            if ( $parseRes['rByName'][ $player ] > 0 ) {
                $dR = 0;

                $rc  = 0;
                $rcN = 0;
                $scoreSum = 0;
                foreach ( $allMatches as $match ) {
                    $oponentR = $parseRes['rByName'][ $match['oponent'] ];
                    if ( $oponentR == 0 ) {
                        continue;
                    }
                    $newNList[ $player ] += 1;

                    $dR += $rList->getDeltaR( $parseRes['rByName'][ $player ], $oponentR, $match['score'] );

                    $rc                  += $oponentR;
                    $rcN ++;

                    $scoreSum += $match['score'];
                }
                $rc /= $rcN;
                $dR *= $parseRes['kByName'][ $player ];
                if ( $dR > 0 ) {
                    $dR = round( $dR );
                } else {
                    $dR = round( $dR, 0, PHP_ROUND_HALF_DOWN );
                }
                $newRList[ $player ] = $parseRes['rByName'][ $player ] + $dR;
            } else {
                $rc  = 0;
                $rcN = 0;
                $scoreSum = 0;
                foreach ( $allMatches as $match ) {
                    $oponentR = $parseRes['rByName'][ $match['oponent'] ];
                    if ( $oponentR == 0 ) {
                        continue;
                    }
                    $newNList[ $player ] += 1;

                    $rc                  += $oponentR;
                    $rcN ++;

                    $scoreSum += $match['score'];
                }
                $rc /= $rcN;

                if ($rc > 0) {
                    if ($scoreSum > $rcN / 2) {
                        $newRList[$player] = round($rc + 40 * ($scoreSum-$rcN/2));
                    }else if($scoreSum < $newNList[$player]/2){
                        $dR = $this->getDRFromTable81a(round($scoreSum / $rcN,2));
                        $newRList[$player] = round($rc + $dR);
                    }else{
                        $newRList[$player] = round($rc);
                    }
                }
            }
            $rcList[$player] = $rc;
            $rcNList[$player] = $rcN;
            $scoreSumList[$player] = $scoreSum;

        }
        arsort( $newRList );

        return [$newRList, $newNList, $parseRes, $rcList, $rcNList, $scoreSumList];
    }

    public function saveNewRList( $newRList, $newNList, $parseRes, $withDeltas = false) {
        $newRText = '';
        foreach ( $newRList as $player => $newR ) {
            $n = 0;
            if (isset($newNList[$player])) {
                $n = $newNList[ $player ];
            }
            $newRText .= $player . "\t" . $newR . "\t" . $parseRes['kByName'][ $player ] . "\t" . $n;
            if ($withDeltas) {
                $delta    = ($newR - $parseRes['rByName'][$player]);
                $newRText .= "\t".$delta;
            }
            $newRText .= "\n";
        }

        $pathToNewRList = RList::getTmpPath() . '/' . $parseRes['rlist'];
        file_put_contents( $pathToNewRList, $newRText );

        return $pathToNewRList;
    }

    public function backupResultsFile( $rlist ) {
        $resultsPath = self::getRResPath() . '/' . $rlist . '-res';
        if ( ! file_exists( $resultsPath ) ) {
            return '';
        }

        $resultsBackupPath = self::getRResBackupPath() . '/' . $rlist . '-res-bk-' . (string) microtime( true );

        if ( ! rename( $resultsPath, $resultsBackupPath ) ) {
            return 'can not backup old results file';
        }

        return '';

    }

    public function getRResultsBackups() {
        $d     = dir( self::getRResBackupPath() );
        $backups = [];
        while ( false !== ( $entry = $d->read() ) ) {
            if ( preg_match( '/^(\d+\.\d+)-res-bk-(\d+\.\d+)$/', $entry, $matches ) ) {
                $rlist = $matches[1];
                $date  = $matches[2];

                $backups[] = [
                    'file'  => $entry,
                    'rlist' => $rlist,
                    'date'  => $date,
                ];
            }
        }

        return $backups;
    }

    public function clearResultsBackups(){
        $resultsBackups = $this->getRResultsBackups();
        foreach ($resultsBackups as $bk){
            unlink(self::getRResBackupPath() . '/' . $bk['file']);
        }
    }

}