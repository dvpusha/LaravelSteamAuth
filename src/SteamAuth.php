<?php

namespace Pusha\LaravelSteamAuth;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Exception;

class SteamAuth implements SteamAuthInterface
{
    /**
     * @var integer|null
     */
    public $steam_ID = null;

    /**
     * @var SteamData
     */
    public $steam_data_user = null;

    /**
     * @var string
     */
    public $authUrl;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    const OPENID_URL = 'https://steamcommunity.com/openid/login';

    /**
     * @var string
     */
    const STEAM_DATA_URL = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s';

    /**
     * @var string
     */
    const STEAM_LEVEL_URL = 'https://api.steampowered.com/IPlayerService/GetSteamLevel/v1/?key=%s&format=json&steamid=%s';

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->authUrl = $this->buildUrl(url(Config::get('steam-auth.redirect_url'), [],
            Config::get('steam-auth.https')));
    }

    /**
     * Validates if the request object has required stream attributes.
     *
     * @return bool
     */
    private function requestIsValid()
    {
        return $this->request->has('openid_assoc_handle') && $this->request->has('openid_signed') && $this->request->has('openid_sig');
    }

    /**
     * Checks the steam login
     *
     * @return bool
     */
    public function validate()
    {
        if (!$this->requestIsValid()) {
            return false;
        }

        try {
            $get = $this->request->all();
            $params = [
                'openid.assoc_handle' => $get['openid_assoc_handle'],
                'openid.signed'       => $get['openid_signed'],
                'openid.sig'          => $get['openid_sig'],
                'openid.ns'           => 'http://specs.openid.net/auth/2.0',
            ];
            $signed = explode(',', $get['openid_signed']);
            foreach ($signed as $item) {
                $val = $get['openid_' . str_replace('.', '_', $item)];
                $params['openid.' . $item] = get_magic_quotes_gpc() ? stripslashes($val) : $val;
            }
            $params['openid.mode'] = 'check_authentication';
            $data = http_build_query($params);
            $context = stream_context_create(array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  =>
                        "Accept-language: en\r\n" .
                        "Content-type: application/x-www-form-urlencoded\r\n" .
                        "Content-Length: " . strlen($data) . "\r\n",
                    'content' => $data,
                ),
            ));
            $result = file_get_contents(self::OPENID_URL, false, $context);
            preg_match("#^https?://steamcommunity.com/openid/id/([0-9]{17,25})#", $get['openid_claimed_id'], $matches);
            $this->steam_ID = is_numeric($matches[1]) ? $matches[1] : 0;
            $this->parseData();

            return (preg_match("#is_valid\s*:\s*true#i", $result) == 1 ? true : false);
        } catch (\Exception $e) {
            app('log')->error($e);
        }
    }

    /**
     * Validates a given URL, ensuring it contains the http or https URI Scheme
     *
     * @param string $url
     *
     * @return bool
     */
    private function validateUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return true;
    }

    /**
     * Build the Steam login URL
     *
     * @param string $return A custom return to URL
     * @return string
     * @throws Exception
     */
    private function buildUrl($return = null)
    {
        if (is_null($return)) {
            $return = url('/', [], Config::get('steam-auth.https'));
        }
        if (!is_null($return) && !$this->validateUrl($return)) {
            throw new Exception('The return URL must be a valid URL with a URI Scheme or http or https.');
        }

        $params = array(
            'openid.ns'         => 'http://specs.openid.net/auth/2.0',
            'openid.mode'       => 'checkid_setup',
            'openid.return_to'  => $return,
            'openid.realm'      => (Config::get('steam-auth.https') ? 'https' : 'http') . '://' . $this->request->server('HTTP_HOST'),
            'openid.identity'   => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        );

        return self::OPENID_URL . '?' . http_build_query($params, '', '&');
    }

    /**
     * Returns the redirect response to login
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirect()
    {
        return redirect($this->getAuthUrl());
    }

    /**
     * Get user data from steam api
     *
     * @return void
     */
    public function parseData()
    {
        if (!is_null($this->steam_ID))
        {
            $api_keys = Config::get('steam-auth.api_keys');
            $api_key  = $api_keys[array_rand($api_keys)];

            $user_info  = file_get_contents(sprintf(self::STEAM_DATA_URL,  $api_key, $this->steam_ID));
            $user_level = file_get_contents(sprintf(self::STEAM_LEVEL_URL, $api_key, $this->steam_ID));

            $user_info  = json_decode($user_info,  true)["response"]["players"][0];
            $user_level = json_decode($user_level, true)['response']['player_level'] ?? null;

            $this->steam_data_user = new SteamData($user_info, $user_level);
        }
    }

    /**
     * Returns the login url
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->authUrl;
    }

    /**
     * Returns the SteamUser info
     *
     * @return SteamData
     */
    public function getUserData()
    {
        return $this->steam_data_user;
    }

    /**
     * Returns the steam id
     *
     * @return bool|string
     */
    public function getSteamID()
    {
        return $this->steam_ID;
    }
}
