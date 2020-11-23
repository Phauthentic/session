<?php

/**
 * Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * @author    Florian Krämer
 * @link      https://github.com/Phauthentic
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace Phauthentic\Session;

use RuntimeException;

/**
 * Session Configuration Abstraction
 *
 * This class provides a way to configure most of the session settings
 *
 * @link https://www.php.net/manual/en/session.security.ini.php
 */
class Config implements ConfigInterface
{
    /**
     * @param array $config
     * @return \Phauthentic\Session\Config
     */
    public static function fromArray(array $config): Config
    {
        $that = new self();

        foreach ($config as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($that, $method)) {
                $that->$method($value);
            }
        }

        return $that;
    }

    /**
     * @inheritDoc
     */
    public function setUseTransSid(bool $useTransSid): ConfigInterface
    {
        $this->iniSet('session.use_trans_sid', $useTransSid);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSerializeHandler(string $handler): ConfigInterface
    {
        $this->iniSet('session.serialize_handler', $handler);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setStrictMode(bool $useStrictMode): ConfigInterface
    {
        $this->iniSet('session.use_strict_mode', $useStrictMode);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCookiePath(string $path): ConfigInterface
    {
        $this->iniSet('session.cookie_path', $path);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCookieHttpOnly(bool $onlyHttp): ConfigInterface
    {
        $this->iniSet('session.cookie_httponly', $onlyHttp);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCookieHttpOnly(): bool
    {
        return (bool)ini_get('session.cookie_httponly');
    }

    /**
     * @inheritDoc
     */
    public function setCookieSecure(bool $secure): ConfigInterface
    {
        $this->iniSet('session.cookie_secure', $secure);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSessionName(string $name): ConfigInterface
    {
        $this->iniSet('session.name', $name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUseCookies(bool $useCookies): ConfigInterface
    {
        $this->iniSet('session.use_cookies', $useCookies);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSavePath(string $path): ConfigInterface
    {
        $this->iniSet('session.save_path', $path);

        return $this;
    }

    /**
     * Checks an edge case for the handler
     *
     * @link https://github.com/php/php-src/commit/a93a51c3bf4ea1638ce0adc4a899cb93531b9f0d
     * @link http://php.net/manual/en/session.configuration.php
     * @param string $handler Handler
     * @return void
     */
    protected function checkHandler(string $handler): void
    {
        if (
            PHP_VERSION_ID >= 70200
            && $handler === 'user'
        ) {
            throw new RuntimeException(
                'You can\'t set the `user` save handler any longer in php >= 7.2. '
                . 'See https://github.com/php/php-src/commit/a93a51c3bf4ea1638ce0adc4a899cb93531b9f0d'
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function setSaveHandler(string $handler): ConfigInterface
    {
        $this->checkHandler($handler);
        $this->iniSet('session.save_handler', $handler);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUseTransSid(): bool
    {
        return (bool)ini_get('session.use_trans_sid');
    }

    /**
     * @inheritDoc
     */
    public function getSerializeHandler(): string
    {
        return ini_get('session.serialize_handler');
    }

    /**
     * @inheritDoc
     */
    public function getUseCookies(): string
    {
        return ini_get('session.use_cookies');
    }

    /**
     * @inheritDoc
     */
    public function getSavePath(): string
    {
        return ini_get('session.save_path');
    }

    /**
     * @inheritDoc
     */
    public function getSaveHandler(): string
    {
        return ini_get('session.save_handler');
    }

    /**
     * @inheritDoc
     */
    public function setGcLifeTime(int $minutes): ConfigInterface
    {
        $this->iniSet('session.gc_maxlifetime', 60 * $minutes);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getGcLifeTime(): int
    {
        return (int)ini_get('session.gc_maxlifetime') / 60;
    }

    /**
     * @inheritDoc
     */
    public function iniSet(string $setting, $value)
    {
        $result = ini_set($setting, (string)$value);
        if ($result === false) {
            throw new RuntimeException(sprintf('Could not set `%s`', $setting));
        }

        return $result;
    }
}
