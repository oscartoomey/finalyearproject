@extends('layout.layout')

<h1> Sign into services to use analysis: </h1>

<div class="boxes">
    <a class="btn btn-outline" href="/signin"> <img src = img/spotify.png alt="logo" height=100> </a>
    <a class="btn btn-outline" href="/lastfm"> <img src = img/lastfm.png alt="logo" height=100> </a>
    <a class="btn btn-outline" href="/stravaSignIn"> <img src = img/strava.png alt="logo" height=100></a>
</div>

<h1> Activity Analysis: </h1>
<?php 

#check if user is signed in

if (isset($_SESSION["spotify"]) == false || $_SESSION["spotify"] == false){
    echo "<div class='alert alert-secondary' role='alert'> Sign into Spotify to see song statistics! </div>";
}

if (isset($_SESSION["strava"]) == false || $_SESSION["strava"] == false){
    echo "<div class='alert alert-secondary' role='alert'> Sign into Strava to see your workouts! </div>";
}

if (isset($_SESSION["lastfmUsername"]) == false){
    echo "<div class='alert alert-secondary' role='alert'> Sign into LastFM to see your song history! </div>";
}
 ?>

<!-- display users runs and statistics if its set !--> 

<?php if (isset($totalRuns)) { ?>
    @foreach($totalRuns as $run)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"> <?php echo $run["workoutName"]; ?> </h5>
            <p class="card-text"> 
                <?php echo gmdate("jS \of F Y H:i", $run["workoutDate"]); ?> 
            </p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"> Workout time: <?php echo gmdate('H:i:s', $run["workoutTime"]); ?></li>
            <li class="list-group-item"> Workout distance: <?php echo number_format(($run["workoutDistance"] / 1000),2); ?> km </li>
            <li class="list-group-item"> Average speed of run: <?php echo $run["averageSpeed"]; ?> km/h </li>
            <li class="list-group-item"> Max speed of run: <?php echo $run["maxSpeed"]; ?> km/h</li>
            <li class="list-group-item"> Pace of run (km per minute): <?php echo $run["pace"]; ?></li>
            <?php if (isset($run["averageHeartRate"])) { ?>
                <li class="list-group-item"> Average heartrate: <?php echo $run["averageHeartRate"]; ?> bpm</li>
                <li class="list-group-item"> Max heartrate: <?php echo $run["maxHeartRate"]; ?> bpm </li>
            <?php } ?>
            <?php if ($run["hasTracks"] == false) { ?>
        </ul>
        <?php } 
        else { ?>
            <li class="list-group-item"> Average song tempo: 
            <?php if (isset($run["averageBPM"])) {
                echo number_format($run["averageBPM"],0);
            } ?> bpm </li>
        </ul>
        Songs Listened to:
        @foreach(array_chunk($run["tracks"],6) as $chunk)
            <div class="row">
            @foreach($chunk as $photos)
                <div class="col-md-1">
                    <div class="album-img">
                    <a class="thumbnail" href=<?php echo $photos->songUrl; ?> >
                        <img class="img-responsive" src=<?php echo $photos->songImage; ?> height="100" alt="">
                        <div class="bottom-left"> <?php echo $photos->songName; ?> </div>
                    </a>
                    </div>
                </div>
            @endforeach
            </div>
        @endforeach
    <?php } ?>
</div>
 @endforeach
 <?php } ?>

