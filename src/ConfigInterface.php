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
     * @param bool $useTransSid
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setUseTransSid(bool $useTransSid): ConfigInterface;

    /**
     * Sets the serialization handler for session data
     *
     * @param string $php
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setSerializeHandler(string $php): ConfigInterface;

    /**
     * Sets use cookies
     *
     * @param bool $useCookies
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setUseCookies(bool $useCookies): ConfigInterface;

    /**
     * Sets the save path
     *
     * @param string $path
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setSavePath(string $path): ConfigInterface;

    /**
     * Sets the save handler for sessions
     * Note that in PHP 7.2.0+ session.save_handler can't be set to user by the user!
     *
     * @link https://github.com/php/php-src/commit/a93a51c3bf4ea1638ce0adc4a899cb93531b9f0d
     * @link http://php.net/manual/en/session.configuration.php
     * @param string $handler
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setSaveHandler(string $handler): ConfigInterface;

    /**
     * Gets the transaction id
     *
     * @return bool
     */
    public function getUseTransSid(): bool;

    /**
     * Gets the serialization handler
     *
     * @return string
     */
    public function getSerializeHandler(): string;

    /**
     * Sets use trans id
     *
     * @return string
     */
    public function getUseCookies(): string;

    /**
     * Gets the save path
     *
     * @return string
     */
    public function getSavePath(): string;

    /**
     * Gets the save handler
     *
     * @return string
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
     * @param bool $onlyHttp
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function setCookieHttpOnly(bool $onlyHttp): ConfigInterface;

    /**
     * @bool
     */
    public function getCookieHttpOnly(): bool;
}
