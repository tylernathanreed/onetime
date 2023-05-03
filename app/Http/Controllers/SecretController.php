<?php

namespace App\Http\Controllers;

use App\Services\Secrets\Contracts\SecretManager;
use Illuminate\Http\Request;

class SecretController extends Controller
{
    /**
     * Creates a new controller instance.
     */
    public function __construct(
        protected SecretManager $secrets
    ) {
        //
    }

    /**
     * Show the form for creating a secret.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Store a secret and show the user the URL for it.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'secret' => array_filter([
                'required',
                'string',
                ! is_null($max = $this->secrets->getRepository()->getMaxLength())
                    ? 'max:' . $max
                    : null
            ]),
            'expires_at' => 'nullable|string'
        ]);

        return view('stored', [
            'slug' => $this->secrets->store($request->secret, $request->expires_at)
        ]);
    }

    /**
     * Show the user the secret landing page.
     */
    public function show(string $slug)
    {
        return view('show', compact('slug'));
    }

    /**
     * Show and destroy the secret.
     */
    public function reveal(string $slug)
    {
        return view('reveal', [
            'secret' => $this->secrets->get($slug)
        ]);
    }

    /**
     * Destroy the secret without showing it.
     */
    public function destroy(string $slug)
    {
        $this->secrets->delete($slug);

        return view('destroyed');
    }
}
