# Bus

A command bus library for PHP 8.2+.

## Installation

```bash
composer require georgeff/bus
```

## Usage

### Defining Commands and Handlers

A command is any plain object. A handler is a callable — typically a class with an `__invoke` method.

```php
class SendEmail
{
    public function __construct(
        public readonly string $to,
        public readonly string $subject,
        public readonly string $body,
    ) {}
}

class SendEmailHandler
{
    public function __invoke(SendEmail $command): void
    {
        // send the email
    }
}
```

### Dispatching Commands

The `Dispatcher` resolves a handler for the given command and executes it.

```php
use Georgeff\Bus\Dispatcher;

$dispatcher = new Dispatcher($resolver);

$dispatcher->dispatch(new SendEmail(
    to: 'user@example.com',
    subject: 'Welcome',
    body: 'Hello!',
));
```

### Handler Resolvers

A resolver takes a command class name and returns a callable handler. Two implementations are provided.

#### SimpleResolver

Instantiates the handler directly. Suited for handlers with no constructor dependencies.

```php
use Georgeff\Bus\Resolver\SimpleResolver;

$resolver = new SimpleResolver($locator);
```

#### PsrContainerResolver

Retrieves the handler from a PSR-11 container. Use this when handlers have dependencies.

```bash
composer require psr/container
```

```php
use Georgeff\Bus\Resolver\PsrContainerResolver;

$resolver = new PsrContainerResolver($container, $locator);
```

### Handler Locators

A locator maps a command class name to a handler class name. Two implementations are provided.

#### InMemoryLocator

Uses an explicit command-to-handler map.

```php
use Georgeff\Bus\Locator\InMemoryLocator;

$locator = new InMemoryLocator([
    SendEmail::class => SendEmailHandler::class,
]);
```

#### ClassNameLocator

Resolves handlers by convention — appends `Handler` to the command class name. `SendEmail` resolves to `SendEmailHandler` in the same namespace.

```php
use Georgeff\Bus\Locator\ClassNameLocator;

$locator = new ClassNameLocator();
```

### Middleware

The `MiddlewareAwareDispatcher` wraps any dispatcher with a middleware pipeline. Each middleware receives the command and a `$next` callable.

```php
use Georgeff\Bus\MiddlewareAwareDispatcher;

$logging = function (object $command, callable $next): mixed {
    echo 'Dispatching ' . $command::class . PHP_EOL;

    $result = $next($command);

    echo 'Dispatched ' . $command::class . PHP_EOL;

    return $result;
};

$dispatcher = new MiddlewareAwareDispatcher(
    new Dispatcher($resolver),
    [$logging],
);

$dispatcher->dispatch(new SendEmail(/* ... */));
```

Middleware executes in the order provided. Each middleware can:
- Run logic before and after the handler
- Short-circuit by not calling `$next`
- Modify the return value

### Full Example

```php
use Georgeff\Bus\Dispatcher;
use Georgeff\Bus\MiddlewareAwareDispatcher;
use Georgeff\Bus\Resolver\PsrContainerResolver;
use Georgeff\Bus\Locator\InMemoryLocator;

$locator = new InMemoryLocator([
    SendEmail::class => SendEmailHandler::class,
]);

$resolver = new PsrContainerResolver($container, $locator);

$dispatcher = new MiddlewareAwareDispatcher(
    new Dispatcher($resolver),
    [$logging, $transaction],
);

$dispatcher->dispatch(new SendEmail(
    to: 'user@example.com',
    subject: 'Welcome',
    body: 'Hello!',
));
```

## License

MIT
