@extends('layout.layout')

@php
use App\Models\Spotify as Spotify;
session_start();

$spotify = new Spotify;

$spotify->auth($_GET['code']);

header('Location: main');
die();


@endphp