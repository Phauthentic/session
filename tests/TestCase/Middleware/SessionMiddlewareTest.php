<?php

declare(strict_types=1);

namespace Phauthentic\Infrastructure\Http\Session\Test\TestCase\Handler;

use Phauthentic\Infrastructure\Http\Session\Middleware\SessionMiddleware;
use Phauthentic\Infrastructure\Http\Session\Session;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Session Middleware TEst
 */
class SessionMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testMiddleware(): void
    {
        $session = new Session();
        $middleware = new SessionMiddleware($session);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();

        $request->expects($this->once())
            ->method('withAttribute')
            ->with('session', $session)
            ->willReturn($this->returnSelf());

        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();

        $result = $middleware->process($request, $handler);
    }
}
