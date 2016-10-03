<?php

namespace Pusha\LaravelSteamAuth;

class SteamData
{
    protected $steam_ID;
    protected $persona_name;
    protected $last_logoff;
    protected $profile_url;
    protected $avatar;
    protected $avatar_medium;
    protected $avatar_full;
    protected $real_name;
    protected $clan_ID;
    protected $created_at;
    protected $country_code;
    protected $profile_state;
    protected $persona_state;
    protected $community_visibility_state;
    protected $comment_permission;
    protected $game_ID;
    protected $game_server_ip;
    protected $game_extra_info;
    protected $state_code;
    protected $city_ID;
    protected $steam_level;

    function __construct($data, $steam_level)
    {
        $this->steam_ID                   = isset($data["steamid"]) ? $data["steamid"] : null;
        $this->persona_name               = isset($data["personaname"]) ? $data["personaname"] : null;
        $this->last_logoff                = isset($data["lastlogoff"]) ? $data["lastlogoff"] : null;
        $this->profile_url                = isset($data["profileurl"]) ? $data["profileurl"] : null;
        $this->avatar                     = isset($data["avatar"]) ? $data["avatar"] : null;
        $this->avatar_medium              = isset($data["avatarmedium"]) ? $data["avatarmedium"] : null;
        $this->avatar_full                = isset($data["avatarfull"]) ? $data["avatarfull"] : null;
        $this->real_name                  = isset($data["realname"]) ? $data["realname"] : null;
        $this->clan_ID                    = isset($data["primaryclanid"]) ? $data["primaryclanid"] : null;
        $this->created_at                 = isset($data["timecreated"]) ? $data["timecreated"] : null;
        $this->country_code               = isset($data["loccountrycode"]) ? $data["loccountrycode"] : null;
        $this->profile_state              = isset($data["profilestate"]) ? $data["profilestate"] : null;
        $this->persona_state              = isset($data["personastate"]) ? $data["personastate"] : null;
        $this->community_visibility_state = isset($data["communityvisibilitystate"]) ? $data["communityvisibilitystate"] : null;
        $this->comment_permission         = isset($data["commentpermission"]) ? $data["commentpermission"] : null;
        $this->game_ID                    = isset($data["gameid"]) ? $data["gameid"] : null;
        $this->game_server_ip             = isset($data["gameserverip"]) ? $data["gameserverip"] : null;
        $this->game_extra_info            = isset($data["gameextrainfo"]) ? $data["gameextrainfo"] : null;
        $this->state_code                 = isset($data["locstatecode"]) ? $data["locstatecode"] : null;
        $this->city_ID                    = isset($data["loccityid"]) ? $data["loccityid"] : null;
        $this->steam_level                = isset($steam_level) ? $steam_level : null;
    }

    /**
     * @return mixed
     */
    public function getSteamID64()
    {
        return $this->steam_ID;
    }

    /**
     * @return mixed
     */
    public function getPersonaName()
    {
        return $this->persona_name;
    }

    /**
     * @return mixed
     */
    public function getLastLogoff()
    {
        return $this->last_logoff;
    }

    /**
     * @return mixed
     */
    public function getProfileUrl()
    {
        return $this->profile_url;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return mixed
     */
    public function getAvatarMedium()
    {
        return $this->avatar_medium;
    }

    /**
     * @return mixed
     */
    public function getAvatarFull()
    {
        return $this->avatar_full;
    }

    /**
     * @return mixed
     */
    public function getRealName()
    {
        return $this->real_name;
    }

    /**
     * @return mixed
     */
    public function getClanID()
    {
        return $this->clan_ID;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @return mixed
     */
    public function getProfileState()
    {
        return $this->profile_state;
    }

    /**
     * @return mixed
     */
    public function getPersonaState()
    {
        return $this->persona_state;
    }

    /**
     * @return mixed
     */
    public function getCommunityVisibilityState()
    {
        return $this->community_visibility_state;
    }

    /**
     * @return mixed
     */
    public function getCommentPermission()
    {
        return $this->comment_permission;
    }

    /**
     * @return mixed
     */
    public function getGameID()
    {
        return $this->game_ID;
    }

    /**
     * @return mixed
     */
    public function getGameServerIP()
    {
        return $this->game_server_ip;
    }

    /**
     * @return mixed
     */
    public function getGameExtraInfo()
    {
        return $this->game_extra_info;
    }

    /**
     * @return mixed
     */
    public function getStateCode()
    {
        return $this->state_code;
    }

    /**
     * @return mixed
     */
    public function getCityID()
    {
        return $this->city_ID;
    }

    /**
     * @return integer
     */
    public function getSteamLevel()
    {
        return $this->steam_level;
    }
}