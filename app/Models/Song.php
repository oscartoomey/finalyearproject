<?php 

namespace App\Models;
use SpotifyWebAPI;
class Song {

    function __construct($songId, $songName, $songArtist, $songAlbum, $playedAt, $songUrl, $songImage, $songGenre) {
        $this->songId = $songId;
        $this->songName = $songName;
        $this->songArtist = $songArtist;
        $this->songAlbum = $songAlbum;
        $this->playedAt = $playedAt;
        $this->songUrl = $songUrl;
        $this->songImage = $songImage;
        $this->songGenre = $songGenre;

        $session = new SpotifyWebAPI\Session(
            '5ff3da8d355e43ffb5e1a8a521932728',
            'ab04ee8f382942169f0a4ce64f726fc7',
            'http://localhost:80/project/public/spotify'
        );

    }

    public function addInfo($songId){

        $spotifyApi = new SpotifyWebAPI\SpotifyWebAPI();

        $spotifyApi->setAccessToken($_SESSION["spotifyAccessToken"]);

        $trackInfo = $spotifyApi->getAudioFeatures($songId);

        $trackInfo = json_encode($trackInfo);
        $trackInfo = json_decode($trackInfo, true);

        $this->danceability = $trackInfo["danceability"];
        $this->length = $trackInfo["duration_ms"];
        $this->energy = $trackInfo["energy"];
        $this->instrumentalness = $trackInfo["instrumentalness"];
        $this->loudness = $trackInfo["loudness"];
        $this->tempo = $trackInfo["tempo"];
        $this->valence = $trackInfo["valence"];
    }
}