<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Phauthentic\Session;

use Adbar\Dot;
use SessionHandlerInterface;

/**
 * This class is a wrapper for the native PHP session functions. It provides
 * several defaults for the most common session configuration
 * via external handlers and helps with using session in cli without any warnings.
 *
 * Sessions can be created from the defaults using `Session::create()` or you can get
 * an instance of a new session by just instantiating this class and passing the complete
 * options you want to use.
 *
 * When specific options are omitted, this class will take its defaults from the configuration
 * values from the `session.*` directives in php.ini. This class will also alter such
 * directives when configuration values are provided.
 */
class Session implements SessionInterface
{
    /**
     * The Session handler instance used as an engine for persisting the session data.
     *
     * @var \SessionHandlerInterface
     */
    protected SessionHandlerInterface $handler;

    /**
     * The Session handler instance used as an engine for persisting the session data.
     *
     * @var \Phauthentic\Session\ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * Indicates whether the sessions has already started
     *
     * @var bool
     */
    protected bool $started = false;

    /**
     * The time in seconds the session will be valid for
     *
     * @var int
     */
    protected int $lifetime = 0;

    /**
     * Whether this session is running under a CLI environment
     *
     * @var bool
     */
    protected bool $isCli = false;

    /**
     * Constructor.
     * ### Configuration:
     * - timeout: The time in minutes the session should be valid for.
     * - cookiePath: The url path for which session cookie is set. Maps to the
     *   `session.cookie_path` php.ini config. Defaults to base path of app.
     * - ini: A list of php.ini directives to change before the session start.
     * - handler: An array containing at least the `class` key. To be used as the session
     *   engine for persisting data. The rest of the keys in the array will be passed as
     *   the configuration array for the engine. You can set the `class` key to an already
     *   instantiated session handler object.
     *
     * @param \Phauthentic\Session\ConfigInterface|null $config The Configuration to apply to this session object
     * @param \SessionHandlerInterface|null $handler
     */
    public function __construct(
        ?ConfigInterface $config = null,
        ?SessionHandlerInterface $handler = null
    ) {
        if ($config !== null) {
            $this->config = $config;
        } else {
            $this->config = new Config();
            $this->config->setUseTransSid(false);
        }

        if ($handler !== null) {
            $this->setSaveHandler($handler);
        }

        $this->lifetime = (int)ini_get('session.gc_maxlifetime');
        $this->isCli = (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg');

        session_register_shutdown();
    }

    /**
     * @return \Phauthentic\Session\ConfigInterface
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * Set the engine property and update the session handler in PHP.
     *
     * @param \SessionHandlerInterface $handler The handler to set
     * @return void
     */
    protected function setSaveHandler(SessionHandlerInterface $handler): void
    {
        if (!headers_sent()) {
            session_set_save_handler($handler, false);
        }

        $this->handler = $handler;
    }

    /**
     * Starts the Session.
     *
     * @return bool True if session was started
     * @throws \Phauthentic\Session\SessionException if the session was already started
     */
    public function start(): bool
    {
        if ($this->started) {
            return true;
        }

        if ($this->isCli) {
            $_SESSION = [];
            $this->setId('cli');

            return $this->started = true;
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            throw SessionException::alreadyStarted();
        }

        if (ini_get('session.use_cookies') && headers_sent($file, $line)) {
            return false;
        }

        if (!session_start()) {
            throw SessionException::couldNotStart();
        }

        $this->started = true;

        if ($this->hasExpired()) {
            $this->destroy();

            return $this->start();
        }

        return $this->started;
    }

    /**
     * @return array|string|null
     */
    protected function time()
    {
        return $this->read('Config.time');
    }

    /**
     * Determine if Session has already been started.
     *
     * @return bool True if session has been started.
     */
    public function started(): bool
    {
        return $this->started || session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Returns true if given variable name is set in session.
     *
     * @param string|null $name Variable name to check for
     * @return bool True if variable is there
     */
    public function check(?string $name = null): bool
    {
        if ($this->exists() && !$this->started()) {
            $this->start();
        }

        if (!isset($_SESSION)) {
            return false;
        }

        return (new Dot($_SESSION))->get($name) !== null;
    }

    /**
     * Returns given session variable, or all of them, if no parameters given.
     *
     * @param string|null $name The name of the session variable (or a path as sent to Hash.extract)
     * @return string|array|null The value of the session variable, null if session not available,
     *   session not started, or provided name not found in the session.
     */
    public function read(?string $name = null)
    {
        if ($this->exists() && !$this->started()) {
            $this->start();
        }

        if (!isset($_SESSION)) {
            return null;
        }

        if ($name === null) {
            return $_SESSION ?? [];
        }

        return (new Dot($_SESSION))->get($name);
    }

    /**
     * Reads and deletes a variable from session.
     *
     * @param string $name The key to read and remove (or a path as sent to Hash.extract).
     * @return mixed The value of the session variable, null if session not available,
     *   session not started, or provided name not found in the session.
     */
    public function consume(string $name)
    {
        if (empty($name)) {
            return null;
        }

        $value = $this->read($name);
        if ($value !== null) {
            $dot = new Dot($_SESSION);
            $dot->delete($name);
            $this->overwrite($_SESSION, (array)$dot->get());
        }

        return $value;
    }

    /**
     * Writes value to given session variable name.
     *
     * @param string|array $name Name of variable
     * @param mixed $value Value to write
     * @return void
     */
    public function write($name, $value = null): void
    {
        if (!$this->started()) {
            $this->start();
        }

        $write = $name;
        if (!is_array($name)) {
            $write = [$name => $value];
        }

        $data = new Dot($_SESSION ?? []);
        foreach ($write as $key => $val) {
            $data->add($key, $val);
        }

        $this->overwrite($_SESSION, $data->get());
    }

    /**
     * Returns the session id.
     *
     * Calling this method will not auto start the session. You might have to manually
     * assert a started session.
     *
     * Passing an id into it, you can also replace the session id if the session
     * has not already been started.
     *
     * Note that depending on the session handler, not all characters are allowed
     * within the session id. For example, the file session handler only allows
     * characters in the range a-z A-Z 0-9 , (comma) and - (minus).
     *
     * @param string|null $id Id to replace the current session id
     * @return string Session id
     */
    public function id(?string $id = null): string
    {
        if ($id !== null && !headers_sent()) {
            $this->setId($id);
        }

        return $this->getId();
    }

    /**
     * Returns the current sessions id
     *
     * @return string
     */
    public function getId(): string
    {
        return (string)session_id();
    }

    /**
     * Sets the session id
     *
     * Calling this method will not auto start the session. You might have to manually
     * assert a started session.
     *
     * Passing an id into it, you can also replace the session id if the session
     * has not already been started.
     *
     * Note that depending on the session handler, not all characters are allowed
     * within the session id. For example, the file session handler only allows
     * characters in the range a-z A-Z 0-9 , (comma) and - (minus).
     *
     * @param string $id Session Id
     * @return $this
     */
    public function setId(string $id): self
    {
        if (headers_sent()) {
            throw SessionException::headersAlreadySent();
        }

        session_id($id);

        return $this;
    }

    /**
     * Removes a variable from session.
     *
     * @param string $name Session variable to remove
     * @return void
     */
    public function delete(string $name): void
    {
        if ($this->check($name)) {
            $this->overwrite($_SESSION, (array)(new Dot($_SESSION))->delete($name));
        }
    }

    /**
     * Used to write new data to _SESSION, since PHP doesn't like us setting the _SESSION var itself.
     *
     * @param array $old Set of old variables => values
     * @param array $new New set of variable => value
     * @return void
     */
    protected function overwrite(&$old, $new)
    {
        if (!empty($old)) {
            foreach ($old as $key => $var) {
                if (!isset($new[$key])) {
                    unset($old[$key]);
                }
            }
        }

        foreach ($new as $key => $var) {
            $old[$key] = $var;
        }
    }

    /**
     * Helper method to destroy invalid sessions.
     *
     * @return void
     */
    public function destroy()
    {
        if ($this->exists() && !$this->started()) {
            $this->start();
        }

        if (!$this->isCli && session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $_SESSION = [];
        $this->started = false;
    }

    /**
     * Clears the session.
     *
     * Optionally it also clears the session id and renews the session.
     *
     * @param bool $renew If session should be renewed, as well. Defaults to false.
     * @return void
     */
    public function clear(bool $renew = false)
    {
        $_SESSION = [];
        if ($renew) {
            $this->renew();
        }
    }

    /**
     * Returns whether a session exists
     *
     * @return bool
     */
    public function exists()
    {
        return !ini_get('session.use_cookies')
            || isset($_COOKIE[session_name()])
            || $this->isCli
            || (ini_get('session.use_trans_sid') && isset($_GET[session_name()]));
    }

    /**
     * Restarts this session.
     *
     * @return void
     */
    public function renew(): void
    {
        if (!$this->exists() || $this->isCli) {
            return;
        }

        $this->start();
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );

        if (session_id()) {
            session_regenerate_id(true);
        }
    }

    /**
     * Returns true if the session is no longer valid because the last time it was
     * accessed was after the configured timeout.
     *
     * @return bool
     */
    public function hasExpired(): bool
    {
        $time = $this->time();
        $result = false;

        $checkTime = $time !== null && $this->lifetime > 0;
        if ($checkTime && (time() - (int)$time > $this->lifetime)) {
            $result = true;
        }

        $this->write('Config.time', time());

        return $result;
    }
}
