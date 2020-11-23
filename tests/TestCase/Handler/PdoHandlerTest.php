<?php

declare(strict_types=1);

namespace Phauthentic\Infrastructure\Http\Session\Test\TestCase\Handler;

use PDO;
use Phauthentic\Infrastructure\Http\Session\Handler\PdoHandler;
use PHPUnit\Framework\TestCase;

/**
 * PDO Handler Test
 */
class PdoHandlerTest extends TestCase
{
    /**
     * @return void
     */
    public function testHandler(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $statement = $pdo->query("CREATE TABLE sessions (
                id VARCHAR(128) NOT NULL,
                data TEXT NOT NULL,
                expires TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            )");

        $statement->execute();

        $handler = new PdoHandler($pdo);
        $result = $handler->write('123', 'foobar');
        $this->assertTrue($result);

        $result = $handler->read('123');
        $this->assertEquals('foobar', $result);

        $result = $handler->destroy('123');
        $this->assertTrue($result);
    }
}
