<?php
declare(strict_types=1);

namespace Burzum\Session;

use RuntimeException;

/**
 * Session Configuration Abstraction
 *
 * Note that in PHP 7.2.0+ session.save_handler can't be set to user by the user!
 *
 * @link https://github.com/php/php-src/commit/a93a51c3bf4ea1638ce0adc4a899cb93531b9f0d
 * @link http://php.net/manual/en/session.configuration.php
 */
class SessionConfig implements SessionConfigInterface
{
    /**
     * @inheritDoc
     */
    public function setGcLifeTime(int $minutes): SessionConfigInterface
    {
        $this->iniSet('session.gc_maxlifetime', 60 * $minutes);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setUseTransSid(bool $useTransSid): SessionConfigInterface
    {
        $this->iniSet('session.use_trans_sid', $useTransSid);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setSerializeHandler(string $handler): SessionConfigInterface
    {
        $this->iniSet('session.serialize_handler', $handler);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setStrictMode(bool $useStrictMode): SessionConfigInterface
    {
        $this->iniSet('session.use_strict_mode', $useStrictMode);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setCookiePath(bool $path): SessionConfigInterface
    {
        $this->iniSet('session.cookie_path', $path);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setCookieHttpOnly(bool $onlyHttp): SessionConfigInterface
    {
        $this->iniSet('session.cookie_httponly', $onlyHttp);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setCookieSecure(bool $secure): SessionConfigInterface
    {
        $this->iniSet('session.cookie_secure', $secure);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setSessionName(bool $name): SessionConfigInterface
    {
        $this->iniSet('session.name', $name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setUseCookies(bool $useCookies): SessionConfigInterface
    {
        $this->iniSet('session.use_cookies', $useCookies);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setSavePath(string $path): SessionConfigInterface
    {
        $this->iniSet('session.save_path', $path);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function setSaveHandler(string $handler): SessionConfigInterface
    {
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            throw new RuntimeException('You cant set the save handler any longer in php >= 7.2');
        }

        $this->iniSet('session.save_handler', $handler);

        return $this;
    }

    /**
     * @inheritDoc
     */
    function getUseTransSid(bool $useTransSid): string
    {
        return ini_get('session.use_trans_sid');
    }

    /**
     * @inheritDoc
     */
    function getSerializeHandler(string $php): string
    {
        return ini_get('session.serialize_handler');
    }

    /**
     * @inheritDoc
     */
    function getUseCookies(bool $useCookies): string
    {
        return ini_get('session.use_cookies');
    }

    /**
     * @inheritDoc
     */
    function getSavePath(string $path): string
    {
        return ini_get('session.save_path');
    }

    /**
     * @inheritDoc
     */
    function getSaveHandler(string $handler): string
    {
        return ini_get('session.save_handler');
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
    public function iniSet($setting, $value)
    {
        $result = ini_set($setting, (string)$value);
        if ($result === false) {
            throw new RuntimeException(sprintf('Could not set `%s`', $setting));
        }

        return $result;
    }
}
