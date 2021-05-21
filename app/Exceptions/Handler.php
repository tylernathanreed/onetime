<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * The displayers used to render exceptions.
     *
     * @var array
     */
    protected $displayers = [
        \App\Exceptions\Displayers\DisplaySecretNotFoundExceptions::class
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Register the view error paths
        $this->registerErrorViewPaths();

        // Determine the current request
        $request = $this->container->bound('request')
            ? $this->container->make('request')
            : Request::createFromGlobals();

        foreach($this->displayers as $displayer) {
            $this->renderable(new DisplayHandler($request, $this->container->make($displayer)));
        }
    }
}
