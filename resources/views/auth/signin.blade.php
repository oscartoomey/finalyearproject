@extends('layout.layout')

@php
        header('Location: ' . 'https://accounts.spotify.com/authorize?client_id=5ff3da8d355e43ffb5e1a8a521932728&redirect_uri=https%3A%2F%2Ffinalyearprojectoscar.herokuapp.com%2Fspotify&response_type=code&scope=playlist-read-private+user-read-private+user-read-recently-played&state=12d7af5432436d28');
        die();
@endphp