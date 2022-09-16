@extends('layout.layout')

@php

header("Location: https://www.strava.com/oauth/authorize?client_id=80033&response_type=code&redirect_uri=http://localhost:80/project/public/stravaAuth&approval_prompt=force&scope=activity:read_all");
die();
@endphp