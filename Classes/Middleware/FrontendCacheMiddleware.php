<?php

declare(strict_types=1);

namespace SFC\Staticfilecache\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Frontend\Cache\CacheInstruction;

class FrontendCacheMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface  $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if (class_exists(CacheInstruction::class)) {
            // @phpstan-ignore-next-line
            $cacheInstruction = $request->getAttribute(
                'frontend.cache.instruction',
                new CacheInstruction(),
            );

            // Disable the cache and give a reason. @todo Why?
            // $cacheInstruction->disableCache('EXT:staticfilecache: Cache is disabled');

            // Write back the cache instruction to the attribute
            $request = $request->withAttribute('frontend.cache.instruction', $cacheInstruction);
        }
        return $handler->handle($request);
    }
}
