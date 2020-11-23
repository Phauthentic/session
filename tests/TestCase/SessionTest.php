<?php

declare(strict_types=1);

namespace Phauthentic\Infrastructure\Http\Session\Test\TestCase;

use Phauthentic\Infrastructure\Http\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Session Test
 */
class SessionTest extends TestCase
{
    /**
     * Test the session
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testReadAndWrite(): void
    {
        $data = ['some' => 'array', 'data' => 'goes here'];

        $session = new Session();

        // Test session start
        $this->assertFalse($session->started());
        $session->start();
        $this->assertTrue($session->started());

        // Test reading and writing
        $this->assertNull($session->read('test'));
        $this->assertFalse($session->check('test'));
        $session->write('test', $data);
        $this->assertEquals($data, $session->read('test'));
        $this->assertTrue($session->check('test'));

        // Test removing the just set value
        $session->delete('test');
        $this->assertNull($session->read('test'));

        // Test consume()
        $this->assertNull($session->read('test2'));
        $this->assertFalse($session->check('test2'));
        $session->write('test2', $data);
        $this->assertTrue($session->check('test2'));
        $this->assertEquals($data, $session->read('test2'));
        $result = $session->consume('test2');
        $this->assertNull($session->read('test2'));
        $this->assertFalse($session->check('test2'));
        $this->assertEquals($data, $result);
    }
}
