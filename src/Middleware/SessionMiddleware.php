<?php
declare(strict_types=1);

namespace Phauthentic\Session\Middleware;

use Phauthentic\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * PSR 15 Middleware
 */
class SessionMiddleware implements MiddlewareInterface
{
    /**
     * Session Attribute in the request object
     *
     * @var string
     */
    protected $sessionAttribute = 'session';

    /**
     * Session Object
     *
     * @var \Phauthentic\Session\SessionInterface
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param \Phauthentic\Session\SessionInterface
     * @param \Psr\Http\Message\ResponseFactoryInterface $responseFactory Factory.
     */
    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }

    /**
     * Sets the identity attribute
     *
     * @param string $attribute Attribute name
     * @return $this
     */
    public function setSessionAttribute(string $attribute): self
    {
        $this->sessionAttribute = $attribute;

        return $this;
    }

    /**
     * Adds an attribute to the request and returns a modified request.
     *
     * @param ServerRequestInterface $request Request.
     * @param string $name Attribute name.
     * @param mixed $value Attribute value.
     * @return ServerRequestInterface
     * @throws RuntimeException When attribute is present.
     */
    protected function addAttribute(ServerRequestInterface $request, string $name, $value): ServerRequestInterface
    {
        if ($request->getAttribute($name)) {
            throw new RuntimeException(sprintf(
                'Request attribute `%s` is already in use.',
                $name
            ));
        }

        return $request->withAttribute($name, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException When request attribute exists.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->addAttribute(
            $request,
            $this->sessionAttribute,
            $this->session
        );

        return $handler->handle($request);
    }
}
