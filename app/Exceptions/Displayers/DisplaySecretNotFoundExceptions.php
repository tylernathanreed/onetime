<?php

namespace App\Exceptions\Displayers;

use App\Models\Secret;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class DisplaySecretNotFoundExceptions
{
    /**
     * Displays the specified exception.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\NotFoundHttpException  $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function display(NotFoundHttpException $e)
    {
        // Determine the previous exception
        $previous = $e->getPrevious();

        // Make sure a previous exception exists
        if(is_null($previous)) {
            return;
        }

        // Make sure the previous exception is a model not found exception
        if(!$previous instanceof ModelNotFoundException) {
            return;
        }

        // Make sure the previous exception is for a secret
        if($previous->getModel() != Secret::class) {
            return;
        }

        // Display the exception
        return view('errors.minimal', [
            'message' => 'Your secret does not exist, or has expired.'
        ]);
    }
}
