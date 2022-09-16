@extends('layout.layout')

<h1> Enter your lastFM username: </h1>
<div class="boxes"> 
<form action="" method="get">
    <input type="text" name="firstname" />
</form> 
</div>

@php
use App\Models\LastFm as LastFm;
session_start();

if (isset($_GET['firstname'])) {
    $_SESSION["lastfmUsername"] = $_GET["firstname"];

    $_SESSION["streaming"] = "lastFM";
    $_SESSION["lastfm"] = true;

    header("Location: main");
    die();
}

@endphp