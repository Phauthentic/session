<?php
declare(strict_types=1);

namespace Burzum\Session;

/**
 * Session Configuration Abstraction Interface
 *
 * @link https://github.com/php/php-src/commit/a93a51c3bf4ea1638ce0adc4a899cb93531b9f0d
 * @link http://php.net/manual/en/session.configuration.php
 */
interface SessionConfigInterface
{
    /**
     * Sets use trans id
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function setUseTransSid(bool $useTransSid): SessionConfigInterface;

    /**
     * Sets the serialization handler for session data
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function setSerializeHandler(string $php): SessionConfigInterface;

    /**
     * Sets use cookies
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function setUseCookies(bool $useCookies): SessionConfigInterface;

    /**
     * Sets the save path
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function setSavePath(string $path): SessionConfigInterface;

    /**
     * Sets the save handler for sessions
     *
     * Note that in PHP 7.2.0+ session.save_handler can't be set to user by the user!
     *
     * @link https://github.com/php/php-src/commit/a93a51c3bf4ea1638ce0adc4a899cb93531b9f0d
     * @return \Burzum\Session\SessionConfigInterface
     */
    function setSaveHandler(string $handler): SessionConfigInterface;

    /**
     * Gets the trans id
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function getUseTransSid(bool $useTransSid): string;

    /**
     * Sets use trans id
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function getSerializeHandler(string $php): string;

    /**
     * Sets use trans id
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function getUseCookies(bool $useCookies): string;

    /**
     * Sets use trans id
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function getSavePath(string $path): string;

    /**
     * Sets use trans id
     *
     * @return \Burzum\Session\SessionConfigInterface
     */
    function getSaveHandler(string $handler): string;
}
