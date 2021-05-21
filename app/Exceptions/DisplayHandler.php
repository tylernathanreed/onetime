<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use ReflectionMethod;
use Throwable;

class DisplayHandler
{
    /**
     * The request being handled.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The exception displayer.
     *
     * @var mixed
     */
    protected $displayer;

    /**
     * Creates a new display handler instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed                     $displayer
     *
     * @return $this
     */
    public function __construct(Request $request, $displayer)
    {
        $this->request = $request;
        $this->displayer = $displayer;
    }

    /**
     * Invokes this display handler.
     *
     * @param  \Throwable  $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Throwable $e)
    {
        // Make sure the exception can be displayed
        if(!$this->canDisplay($e)) {
            return;
        }

        // Determine the displayer response
        $response = $this->displayer->display($e);

        // Transform the response
        return Router::toResponse($this->request, $response);
    }

    /**
     * Returns whether or not the specified exception can be displayed.
     *
     * @param  \Throwable  $e
     *
     * @return boolean
     */
    public function canDisplay(Throwable $e)
    {
        // Make sure the display method exists
        if(!method_exists($this->displayer, 'display')) {
            return false;
        }

        // Make sure the display method accepts the exception
        if(!$this->accepts($e)) {
            return false;
        }

        // The exception can be displayed
        return true;
    }

    /**
     * Returns whether or not the specified exception can be accepted.
     *
     * @param  \Throwable  $e
     *
     * @return boolean
     */
    public function accepts(Throwable $e)
    {
        // Reflect the display method
        $method = new ReflectionMethod($this->displayer, 'display');

        // Determine the first parameter
        $parameter = $method->getParameters()[0] ?? null;

        // Make sure the parameter exists
        if(is_null($parameter)) {
            return false;
        }

        // Determine the accepted type
        $accepted = $parameter->getType()->getName();

        // Return whether or not the exception is accepted
        return is_a($e, $accepted);
    }
}
