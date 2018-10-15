<?php
declare(strict_types=1);

namespace Burzum\Session\Test\TestCase;

use Burzum\Session\Session;
use PHPUnit\Framework\TestCase;

/**
 * Session Test
 */
class SessionTest extends TestCase
{
    /**
     * testRead
     *
     * @return void
     */
    public function testReadAndWrite(): void
    {
        $data = ['some' => 'array', 'data' => 'goes here'];
        $session = new Session();
        $session->write('test', $data);
        $this->assertEquals($data, $session->read('test'));
    }
}
