<?php

namespace Cake\Http\Session;

use InvalidArgumentException;
use Psr\Cache\CacheItemPoolInterface;
use SessionHandlerInterface;

/**
 * CacheSession provides method for saving sessions into cache engines
 * implementing the PSR cache interfaces
 *
 * @link https://www.php-fig.org/psr/psr-6/
 */
class PsrCacheHandler implements SessionHandlerInterface
{
    /**
     * PSR Cache Item Pool
     *
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    protected $cachePool;

    protected $cacheItemClass;

    /**
     * Constructor.
     *
     * @param \Psr\Cache\CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cachePool = $cacheItemPool;
    }

    /**
     * Method called on open of a database session.
     *
     * @param string $savePath The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool Success
     */
    public function open($savePath, $name)
    {
        return true;
    }

    /**
     * Method called on close of a database session.
     *
     * @return bool Success
     */
    public function close()
    {
        return true;
    }

    /**
     * Method used to read from a cache session.
     *
     * @param string|int $id ID that uniquely identifies session in cache.
     * @return string Session data or empty string if it does not exist.
     */
    public function read($id)
    {
        $cacheItem = $this->cachePool->getItem($id);

        $value = $cacheItem->get();

        return $value ?? null;
    }

    /**
     * Helper function called on write for cache sessions.
     *
     * @param string|int $id ID that uniquely identifies session in cache.
     * @param mixed $data The data to be saved.
     * @return bool True for successful write, false otherwise.
     */
    public function write($id, $data)
    {
        if (!$id) {
            return false;
        }

        if ($this->cachePool->hasItem($id)) {
            $cacheItem = $this->cachePool->getItem($id);
        } else {
            $class = $this->cacheItemClass;
            $cacheItem = new $class();
        }

        $cacheItem->set($data);

        return true;
    }

    /**
     * Method called on the destruction of a cache session.
     *
     * @param string|int $id ID that uniquely identifies session in cache.
     * @return bool Always true.
     */
    public function destroy($id)
    {
        $this->cachePool->deleteItem($id);

        return true;
    }

    /**
     * Helper function called on gc for cache sessions.
     *
     * @param int $maxlifetime Sessions that have not updated for the last maxlifetime seconds will be removed.
     * @return bool Always true.
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}
