<?php

declare(strict_types=1);

namespace Phauthentic\Infrastructure\Http\Session\Handler;

use PDO;
use PDOStatement;
use RuntimeException;
use SessionHandlerInterface;

/**
 * Generic PDO Session Handler
 */
class PdoHandler implements SessionHandlerInterface
{
    /**
     * PDO Object
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * Session table name
     *
     * @var string
     */
    protected $table = 'sessions';

    /**
     * Session table field map
     *
     * @var array
     */
    protected $fieldMap = [
        'sid' => 'id',
        'expires' => 'expires',
        'data' => 'data'
    ];

    /**
     * Constructor
     *
     * @param \PDO $pdo PDO Object
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Sets the table name
     *
     * @param string $table Table name
     * @return void
     */
    public function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     * Close the session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close()
    {
        return true;
    }

    /**
     * Destroy a session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $session_id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($session_id)
    {
        $statement = $this->prepareStatement("DELETE FROM {$this->table} WHERE id = :sid");

        return $statement->execute([
            'sid' => $session_id
        ]);
    }

    /**
     * Cleanup old sessions
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $maxlifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * Initialize session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * Read session data
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $session_id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($session_id)
    {
        $statement = $this->prepareStatement("SELECT * FROM {$this->table} WHERE id = :sid");
        $statement->execute([
            'sid' => $session_id
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result['data'] ?? '';
    }

    /**
     * Write session data
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $session_id The session id.
     * @param string $session_data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function write($session_id, $session_data)
    {
        $statement = $this->pdo->prepare("INSERT INTO {$this->table} (id, data, expires) VALUES (?,?,?)");

        return $statement->execute([
            $session_id, $session_data, time()
        ]);
    }

    /**
     * @param string $sql
     * @return bool|\PDOStatement
     */
    protected function prepareStatement(string $sql)
    {
        $statement = $this->pdo->prepare($sql);
        if (!$statement instanceof PDOStatement) {
            throw new RuntimeException($this->pdo->errorInfo()[2]);
        }

        return $statement;
    }
}
