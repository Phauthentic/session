<?php

declare(strict_types=1);

namespace Phauthentic\Session;

/**
 * Session Interface
 */
interface SessionInterface
{
    /**
     * Returns given session variable, or all of them, if no parameters given.
     *
     * @param string|null $name The name of the session variable (or a path as sent to Hash.extract)
     * @return string|array|null The value of the session variable, null if session not available,
     *   session not started, or provided name not found in the session.
     */
    public function read(?string $name = null);

    /**
     * Writes value to given session variable name.
     *
     * @param string|array $name Name of variable
     * @param mixed $value Value to write
     * @return void
     */
    public function write($name, $value = null): void;

    /**
     * Removes a variable from session.
     *
     * @param string $name Session variable to remove
     * @return void
     */
    public function delete(string $name): void;

    /**
     * Returns the session id.
     *
     * Calling this method will not auto start the session. You might have to manually
     * assert a started session.
     *
     * Passing an id into it, you can also replace the session id if the session
     * has not already been started.
     * Note that depending on the session handler, not all characters are allowed
     * within the session id. For example, the file session handler only allows
     * characters in the range a-z A-Z 0-9 , (comma) and - (minus).
     *
     * @param string|null $id Id to replace the current session id
     * @return string Session id
     */
    public function id(?string $id = null): string;
}
