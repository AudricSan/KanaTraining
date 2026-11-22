<?php

namespace Kanatraining;

class env
    {
    private $env = [
        //APP
        'APP_NAME'    => 'Kanatraining',
        'APP_KEY'     => '018b94b5-2f26-78cb-8dec-7ab408c0a230',
        'APP_VERSION' => '2.0.0',

        // DATABASE
        'DB_HOST'     => 'localhost',
        'DB_USERNAME' => 'root',
        'DB_PASSWORD' => '',
        'DB_NAME'     => 'kanatraining',
        'DB_PORT'     => 3306,

        // Twitch CONFIG
        'TWITCH_CLIENT_ID'      =>'2221elfobx25s0vp51fuo29atzm5tm',
        'TWITCH_CLIENT_SECRET'  =>'7cv17z4m7vys5hltmokrn6uafkaybj',
        'TWITCH_SCOPES'         =>'user:read:broadcast user:read:email',
    ];

    /**
     * Starts the session with hardened cookie params (httponly, samesite=Lax,
     * secure when served over HTTPS). Call this instead of session_start()
     * on every entry point that touches $_SESSION.
     */
    public static function startSession(): void
        {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => $secure,
        ]);

        session_start();
        }

    public function env ( $key, $default = null )
        {
        if ( $key )
            {
            return $this->env[ $key ];
            } else
            {
            return $default;
            }
        }

    public function checkInput ( $data )
        {
        $data = trim ( $data );
        $data = stripslashes ( $data );
        $data = htmlspecialchars ( $data );
        return $data;
        }

    public function isoToEmoji ( string $code )
        {
        return implode (
            '',
            array_map (
                fn ( $letter ) => mb_chr ( ord ( $letter ) % 30 + '0c1f1E5' ),
                str_split ( $code ),
            ),
        );
        }
    }
