<?php 
namespace App\Models;
use SpotifyWebAPI;
use App\Models\Song as Song;

class Spotify {

    public function __construct() {

        $session = new SpotifyWebAPI\Session(
                    '5ff3da8d355e43ffb5e1a8a521932728',
                    'ab04ee8f382942169f0a4ce64f726fc7',
                    'http://localhost:80/project/public/spotify'
                );

        $spotifyApi = new SpotifyWebAPI\SpotifyWebAPI();

        try {
            $spotifyApi->setAccessToken($_SESSION["spotifyAccessToken"]); 
            $_SESSION["spotify"] = true;       
        } catch (\Exception $e) {
            $_SESSION["spotify"] = false;
        }
        $this->session = $session;
        $this->spotifyApi = $spotifyApi;
    }

    public function auth($code){
        $this->session->requestAccessToken($code);

        $accessToken = $this->session->getAccessToken();
        $refreshToken = $this->session->getRefreshToken();
        
        // Store the access and refresh tokens somewhere. In a session for example
        $_SESSION["spotifyAccessToken"] = $accessToken;
        $_SESSION["spotifyRefreshToken"] = $refreshToken;
        $_SESSION["streaming"] = "spotify";
        $_SESSION["spotify"] = true;
    }

    public function getRecentTracks(){

        $tracks = $this->spotifyApi->getMyRecentTracks();
        $tracks = json_encode($tracks);
        $tracks = json_decode($tracks, true);
        $tracks = $tracks["items"];
        $this->tracks = $tracks;
    }

    public function createSongArray($startDate, $totalTime){
        $songArray = array();
        foreach($this->tracks as $song) {
            $runTime = strtotime($song["played_at"]) + 3600;
            if (($startDate <= $runTime) && ($runTime <= $totalTime)) {
                $songId = $song["track"]["id"];
                $songName = $song["track"]["name"];
                $songAlbum = $song["track"]["album"]["name"];
                $songArtist = $song["track"]["artists"][0]["name"];
                $playedAt = $song["played_at"];
                $songUrl = $song["track"]["external_urls"]["spotify"];
                $songImage = $song["track"]["album"]["images"][0]["url"];
                ${"song$songName"} = new Song($songId, $songName, $songAlbum, $songArtist, $playedAt, $songUrl, $songImage);
                ${"song$songName"}->addInfo($songId);
                $songArray[] = ${"song$songName"};
            }
        }
        return $songArray;
    }
}











?>