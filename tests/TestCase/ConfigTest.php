<?php
declare(strict_types=1);

namespace Phauthentic\Session\Test\TestCase;

use Phauthentic\Session\Config;
use PHPUnit\Framework\TestCase;

/**
 * Config Test
 */
class ConfigTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @return void
     */
    public function testConfig(): void {
        $config = (new Config())
            ->setCookieHttpOnly(true)
            ->setGcLifeTime(300)
            ->setUseTransSid(true);
    }
}
