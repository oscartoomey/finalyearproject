<?php 

namespace App\Models;
use App\Models\Song;
class Run {

    function __construct($workoutName, $workoutDate, $workoutDistance, $workoutTime, $averageSpeed, $maxSpeed) {
        $this->workoutName = $workoutName;
        $this->workoutDate = $workoutDate;
        $this->workoutDistance = $workoutDistance;
        $this->workoutTime = $workoutTime;
        $this->averageSpeed = ($averageSpeed * 3.6);
        $this->maxSpeed = ($maxSpeed * 3.6);
    }

    public function addHeartRate($averageHeartRate, $maxHeartRate) {
        $this->averageHeartRate = $averageHeartRate;
        $this->maxHeartRate = $maxHeartRate;
    }

    public function addTracks($tracks){
        if (empty($tracks)) {
            $this->tracks = $tracks;
            $this->hasTracks = false;
        }
        else{
            $this->tracks = $tracks;
            $this->hasTracks = true;
        }
    }

    public function addTotalTime(int $workoutDate, int $workoutTime) {
        $totalTime = $workoutDate + $workoutTime;
        
        $this->totalTime = $totalTime;
    }

    public function workoutPace(){ 
        $pace = ($this->workoutTime / ($this->workoutDistance / 1000));
        $pace = date('i:s', $pace);
        $this->pace = $pace;
    }

    public function workoutAverageBpm() {
        if ($this->tracks == null){
            $this->averageBPM = null;
        }
        else{
            foreach ($this->tracks as $song) {
                $bpmTotal[] = $song->tempo;
            }
            $average = array_sum($bpmTotal)/count($bpmTotal);
            $this->averageBPM = $average;
        }
    }
}

?>