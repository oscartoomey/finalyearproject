@extends('layout.layout')

<?php
use App\Models\Strava as Strava;

session_start();

$strava = new Strava;

$strava->auth($_GET["code"]);

header('Location: main');
die();

?>


