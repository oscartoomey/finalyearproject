<?php 
namespace App\Models;
use LastFmApi\Api\AuthApi;
use LastFmApi\Api\UserApi;
use LastFmApi\Api\TrackApi;
use App\Models\Song as Song;
use SpotifyWebAPI;


class LastFm {

    public function __construct($user) {
        $this->apiKey = '79442229b53529893d8352bc2266bfbb'; //required
        $auth = new AuthApi('setsession', array('apiKey' => $this->apiKey));
        $this->lastfmApi = new UserApi($auth);
        $this->trackApi = new TrackApi($auth);
        $this->user = $user;
    }

    public function getRecentTracks($startDate, $totalTime) {
        $recentTracks = $this->lastfmApi->getRecentTracks(array("user" => $this->user), $startDate, $totalTime);
        $songArray = array();
        if (is_null($recentTracks)){
            return $songArray;
        }
        else {
            foreach($recentTracks as $song) {
                if (isset($song["nowplaying"])) {
                    continue;
                }
                else{
                    $runTime = $song["date"];
                    $songId = $song["mbid"];
                    $songName = $song["name"];
                    $songAlbum = $song["album"]["name"];
                    $songArtist = $song["artist"]["name"];
                    $playedAt = $song["date"];
                    $songUrl = $song["url"];
                    $songImage = $song["images"]["large"];
                    $tagSearch = ['artist' => $songArtist, 'track' => $songName];
                    try{
                    $temp = $this->trackApi->getTopTags($tagSearch);
                    }
                    catch (\Throwable $e){
                        $temp = null;
                    }
                    if ($temp !== null){
                        $songGenre = $temp["tags"][0]["name"];
                    }
                    else {
                        $songGenre = 'null';
                    }
                    ${"song$songName"} = new Song($songId, $songName, $songAlbum, $songArtist, $playedAt, $songUrl, $songImage, $songGenre);
                    $trackId = $this->searchSpotify($songName);
                    if (isset($trackId)){
                        ${"song$songName"}->addInfo($trackId);
                    }
                    $songArray[] = ${"song$songName"};
                }
            }
        return $songArray;
        }
    }
    
    public function searchSpotify($songName){
        try{
            $options = array(null, 5, null);
            $session = new SpotifyWebAPI\Session(
                '5ff3da8d355e43ffb5e1a8a521932728',
                'ab04ee8f382942169f0a4ce64f726fc7',
                'http://localhost:80/project/public/spotify'
            );

            $spotifyApi = new SpotifyWebAPI\SpotifyWebAPI();

            $spotifyApi->setAccessToken($_SESSION["spotifyAccessToken"]);
            $results = $spotifyApi->search($songName, 'track', $options);
            $trackInfo = json_encode($results);
            $trackInfo = json_decode($trackInfo, true);
            $trackId = $trackInfo["tracks"]["items"][0]["id"];
            $_SESSION["spotify"] = true;   
            return $trackId;
        } catch (\Exception $e) {
            $_SESSION["spotify"] = false;
        }
    }
}

?>