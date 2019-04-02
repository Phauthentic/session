<?php
declare(strict_types=1);

namespace Phauthentic\Session;

use RuntimeException;

/**
 * Session Configuration Abstraction
 */
class Config implements ConfigInterface
{
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
    public function setCookiePath(bool $path): ConfigInterface
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
    public function setSessionName(bool $name): ConfigInterface
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
        if (version_compare(PHP_VERSION, '7.2.0', '>=')
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
