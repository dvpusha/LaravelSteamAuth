<?php

namespace Pusha\LaravelSteamAuth;

interface SteamAuthInterface
{
    public function redirect();
    public function validate();
    public function getAuthUrl();
    public function getSteamID();
    public function getUserData();
}