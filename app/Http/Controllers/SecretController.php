<?php

namespace App\Http\Controllers;

use App\Services\Secrets\Contracts\Factory as SecretFactory;
use Illuminate\Http\Request;

class SecretController extends Controller
{
    /**
     * The secret factory implementation.
     */
    protected SecretFactory $secrets;

    /**
     * Creates a new controller instance.
     */
    public function __construct(SecretFactory $secrets)
    {
        $this->secrets = $secrets;
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
        // Validate the request
        $this->validate($request, [
            'secret' => 'required|string|max:' . $this->secrets->byteLimit(),
            'expires_at' => 'nullable|string'
        ]);

        // Display the secret url
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
        // Delete the secret
        $this->secrets->delete($slug);

        // Display the destroyed page
        return view('destroyed');
    }
}
