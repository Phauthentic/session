<?php
declare(strict_types=1);

namespace Phauthentic\Session;

use PDO;
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
     * Close the session
     *
     * @link http://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close() {
        // TODO: Implement close() method.
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
        $this->pdo->prepare('DELETE FROM :table WHERE sid = :sid');
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
        $statement = $this->pdo->prepare('SELECT * FROM :table WHERE :sid = :sessionId');
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
        $statement = $this->pdo->prepare('SELECT * FROM :table WHERE :sid = :sessionId');
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
        $statement = $this->pdo->prepare('SELECT * FROM :table WHERE :sid = :sessionId');
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
        $statement = $this->pdo->prepare('INSERT INTO :table (:sid) (:sessionId)');

        return $statement->execute([
            'table' => $this->table,
            'sid' => $this->fieldMap['sid'],
            'sessionId' => $session_id,
            'sessionData' => $session_data
        ]);
    }
}
