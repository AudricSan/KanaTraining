<?php

namespace Kanatraining;

class TwitchOAuth
{
    private env $env;
    private string $redirectUri;

    public function __construct(env $env, string $redirectUri)
    {
        $this->env = $env;
        $this->redirectUri = $redirectUri;
    }

    public function buildAuthorizeUrl(string $state): string
    {
        $params = [
            'client_id'     => $this->env->env('TWITCH_CLIENT_ID'),
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => $this->env->env('TWITCH_SCOPES'),
            'state'         => $state,
        ];

        return 'https://id.twitch.tv/oauth2/authorize?' . http_build_query($params);
    }

    /**
     * Exchange an authorization code for an access token.
     * Returns the access token string, or null on failure.
     */
    public function exchangeCodeForToken(string $code): ?string
    {
        $params = [
            'client_id'     => $this->env->env('TWITCH_CLIENT_ID'),
            'client_secret' => $this->env->env('TWITCH_CLIENT_SECRET'),
            'code'          => $code,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirectUri,
        ];

        $response = $this->post('https://id.twitch.tv/oauth2/token', $params);

        return $response['access_token'] ?? null;
    }

    /**
     * Fetch the Twitch profile of the authenticated user.
     * Returns an assoc array (id, login, display_name, email, profile_image_url, ...), or null on failure.
     */
    public function fetchUserProfile(string $accessToken): ?array
    {
        $ch = curl_init('https://api.twitch.tv/helix/users');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Client-Id: ' . $this->env->env('TWITCH_CLIENT_ID'),
            ],
            CURLOPT_TIMEOUT => 10,
        ]);

        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($body === false || $status !== 200) {
            return null;
        }

        $decoded = json_decode($body, true);

        return $decoded['data'][0] ?? null;
    }

    private function post(string $url, array $params): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_TIMEOUT => 10,
        ]);

        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($body === false || $status !== 200) {
            return null;
        }

        return json_decode($body, true);
    }
}
