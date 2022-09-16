<?php 

namespace App\Models;
Use Iamstuartwilson\StravaApi as StravaT;
use \Exception as Exception;

class Strava {

    function __construct(){
        $api = new StravaT(
            $clientId = 80033,
            $clientSecret = '3fcfc7e84d9de182d321dd34c47ae8942ea76f77'
        );
        
        // Set access tokens, if not set variable to let the view know to display message
        try {
            $api->tokenExchange($_SESSION['strava_access_token']);
            $api->setAccessToken($_SESSION["strava_access_token"], $_SESSION['strava_refresh_token'], $_SESSION['strava_access_token_expires_at']);
            $_SESSION["strava"] = true;
        } catch(Exception $e) {
            $_SESSION["strava"] = false;
        }
        $this->api = $api;
    }

    public function getActivities(){
        
        //try and except to avoid errors for the user when unauthorised
        try {
            $_SESSION["strava"] = true;
            $activities = $this->api->get('/athlete/activities');
            return $activities;
        } catch(\Exception $e) {
            $_SESSION["strava"] = false;
        }    
    }

    public function auth($code){
        // authorisation code for logging in using the access code
        
        $_SESSION['stravaCode'] = $code;
        $result = $this->api->tokenExchange($code);
        
        $accessToken = $result->access_token;
        $refreshToken = $result->refresh_token;
        $expiresAt = $result->expires_at;
        $this->api->setAccessToken(
            $accessToken,
            $refreshToken,
            $expiresAt);
        $_SESSION["strava_access_token"] = $accessToken;
        $_SESSION["strava_refresh_token"] = $refreshToken;
        $_SESSION["strava_access_token_expires_at"] = $expiresAt;
        
        $this->api->setAccessToken($accessToken, $refreshToken, $expiresAt);
    }

}