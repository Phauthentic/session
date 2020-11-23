<?php

declare(strict_types=1);

namespace Phauthentic\Infrastructure\Http\Session\Test\TestCase;

use Phauthentic\Infrastructure\Http\Session\Config;
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
    public function testConfig(): void
    {
        $config = (new Config())
            ->setCookieHttpOnly(true)
            ->setGcLifeTime(300)
            ->setUseTransSid(true)
            ->setSavePath('tmp')
            ->setCookiePath('cookie-path');

        $this->assertEquals('tmp', $config->getSavePath());
        $this->assertEquals(300, $config->getGcLifeTime());
        $this->assertEquals('cookie-path', $config->getCookiePath());
        $this->assertEquals('php', $config->getSerializeHandler());
        $this->assertTrue($config->getCookieHttpOnly());
        $this->assertTrue($config->getUseTransSid());
    }

    /**
     * @runInSeparateProcess
     * @return void
     */
    public function testConfigFromArray(): void
    {
        $config = Config::fromArray([
            'useTransSid' => true,
            'cookiePath' => 'cookie-path',
            'gcLifeTime' => 300,
            'savePath' => 'tmp',
            'cookieHttpOnly' => true
        ]);

        $this->assertEquals('tmp', $config->getSavePath());
        $this->assertEquals(300, $config->getGcLifeTime());
        $this->assertEquals('cookie-path', $config->getCookiePath());
        $this->assertEquals('php', $config->getSerializeHandler());
        $this->assertTrue($config->getCookieHttpOnly());
        $this->assertTrue($config->getUseTransSid());
    }
}
