<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Run as Run;
use App\Models\Song as Song;
use App\Models\Spotify as Spotify;
use App\Models\LastFm as LastFm;
use App\Models\Strava as Strava;

class RunController extends Controller
{
    public function index() {
        session_start();
        $totalRuns = [];

        if (isset($_SESSION["strava_access_token"])){
            $strava = new Strava();
            $activities = $strava->getActivities();
        }

        if (isset($_SESSION["spotifyAccessToken"])){
            $spotify = new Spotify();
        }
        
        if (isset($_SESSION["lastfmUsername"])){
            if ($_SESSION["lastfmUsername"] == null){
            $lastFM = new LastFM($_SESSION["lastfmUsername"]);
        }}

        $i = 0;

        if (isset($activities)) {
            foreach ($activities as $item) {
                $item = json_encode($item); //encoding and decoding so it can be parsed as an array
                $item = json_decode($item, true);
                if ($item["type"] == "Run") {
                    $i++; #used to name each run 
                    ${"run$i"} = new Run(
                        $item["name"], 
                        strtotime($item["start_date_local"]), 
                        $item["distance"], 
                        $item["elapsed_time"], 
                        $item["average_speed"], 
                        $item["max_speed"]
                    );
                    if ($item["has_heartrate"] == true) {
                        ${"run$i"}->addHeartRate($item["average_heartrate"], $item["max_heartrate"]);
                    }
                    ${"run$i"}->addTotalTime(strtotime($item["start_date_local"]), $item["elapsed_time"]);
                    ${"run$i"}->workoutPace();
                    $startDate = strtotime($item["start_date_local"]);
                    if (isset($_SESSION["lastfmUsername"])){
                        $lastFM = new LastFm($_SESSION["lastfmUsername"]);
                        try{
                            $tracks = $lastFM->getRecentTracks($startDate, ${"run$i"}->totalTime);
                        }
                        catch(Exception $e){
                            sleep(5);
                            $tracks = $lastFM->getRecentTracks($startDate, ${"run$i"}->totalTime);
                        }
                        ${"run$i"}->addTracks($tracks);
                        if (isset($_SESSION["spotify"])) {
                            if ($_SESSION["spotify"] == true) {
                                ${"run$i"}->workoutAverageBpm();
                                if (${"run$i"}->hasTracks == true) {
                                    $labels[] = ${"run$i"}->workoutName;
                                    $formattedData[] = [
                                        "x"     => (int) ${"run$i"}->averageBPM,
                                        "y"     => (int) ${"run$i"}->pace
                                    ];
                                    $_SESSION["labels"] = $labels;
                                    $_SESSION["data"] = $formattedData;
                                }
                            }
                        }
                        $totalRuns[] = (array) ${"run$i"};
                        $_SESSION["totalRuns"] = $totalRuns;
                    }
                }
                else {
                    continue;
                }
            }
            return view('pages/main')->with('totalRuns', $totalRuns);
        }
        return view('pages/main')->with('totalRuns', $totalRuns);
    }
}
