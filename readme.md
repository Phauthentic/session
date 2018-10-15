# Phassion - A php session abstraction

A framework agnostic session library for php with convenient dot notation access.

**This is not yet production ready!**

## How to use it

```php
use Burzum\Session\Session ;

$config = (new SessionConfig())
    ->setName('my-app')
    ->setGcMaxLifeTime(60);

$session = new Session();
$session->write('user', ['username' => 'foo']);

echo $session->read('user.username';
```

## Copyright & License

Licensed under the [MIT license](LICENSE.txt).

* Copyright (c) Florian Krämer
* Copyright (c) [Cake Software Foundation, Inc.](https://cakefoundation.org)
