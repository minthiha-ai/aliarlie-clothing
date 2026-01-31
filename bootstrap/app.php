<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your session expired. Please refresh the page and try again.'], 419);
            }

            return redirect()
                ->back()
                ->withInput($request->except('_token'))
                ->with('error', 'Your session expired. Please refresh the page and try again.');
        });

        $exceptions->render(function (ThrottleRequestsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Too many attempts. Please try again later.'], 429);
            }

            if ($request->isMethod('POST') && $request->routeIs('contact.store')) {
                return redirect()
                    ->route('contact')
                    ->withInput($request->except('_token'))
                    ->with('error', "You've sent too many messages. Please wait a minute before trying again.");
            }

            return null;
        });
    })->create();
