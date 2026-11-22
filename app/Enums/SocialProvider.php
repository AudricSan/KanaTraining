<?php

namespace App\Enums;

/**
 * OAuth providers a student can log in with. The value is the Socialite driver name
 * and the key under config/services.php.
 */
enum SocialProvider: string
{
    case Twitch = 'twitch';
    case Google = 'google';
    case Discord = 'discord';

    public function label(): string
    {
        return match ($this) {
            self::Twitch => 'Twitch',
            self::Google => 'Google',
            self::Discord => 'Discord',
        };
    }

    /**
     * A provider only shows up once its application keys are set in .env.
     */
    public function isConfigured(): bool
    {
        return filled(config("services.{$this->value}.client_id"))
            && filled(config("services.{$this->value}.client_secret"));
    }

    /**
     * @return list<self>
     */
    public static function configured(): array
    {
        return array_values(array_filter(self::cases(), fn (self $p) => $p->isConfigured()));
    }
}
