<?php
declare(strict_types=1);

namespace Phauthentic\Session;

/**
 * Session Configuration Abstraction Interface
 *
 * @link http://php.net/manual/en/session.configuration.php
 */
interface ConfigInterface
{
    /**
     * Sets use trans id
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setUseTransSid(bool $useTransSid): ConfigInterface;

    /**
     * Sets the serialization handler for session data
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setSerializeHandler(string $php): ConfigInterface;

    /**
     * Sets use cookies
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setUseCookies(bool $useCookies): ConfigInterface;

    /**
     * Sets the save path
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setSavePath(string $path): ConfigInterface;

    /**
     * Sets the save handler for sessions
     * Note that in PHP 7.2.0+ session.save_handler can't be set to user by the user!
     *
     * @link https://github.com/php/php-src/commit/a93a51c3bf4ea1638ce0adc4a899cb93531b9f0d
     * @link http://php.net/manual/en/session.configuration.php
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setSaveHandler(string $handler): ConfigInterface;

    /**
     * Gets the transaction id
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function getUseTransSid(): bool;

    /**
     * Gets the serialization handler
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function getSerializeHandler(): string;

    /**
     * Sets use trans id
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function getUseCookies(): string;

    /**
     * Sets use trans id
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function getSavePath(): string;

    /**
     * Sets use trans id
     *
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function getSaveHandler(): string;

    /**
     * Sets the garbage collection life time in minutes
     *
     * @param int $minutes GC life time in minutes
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setGcLifeTime(int $minutes): ConfigInterface;

    /**
     * Returns the GC life time in minutes
     *
     * @return int
     */
    public function getGcLifeTime(): int;

    /**
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setCookieHttpOnly(bool $onlyHttp): ConfigInterface;

    /**
     * @bool
     */
    public function getCookieHttpOnly(): bool;
}
