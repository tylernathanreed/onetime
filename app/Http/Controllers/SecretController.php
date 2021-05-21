<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SecretController extends Controller
{
    /**
     * Show the form for creating a secret.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Store a secret and show the user the URL for it.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'secret' => 'required|string|max:' . Secret::MAX_LENGTH
        ]);

        // Create a new secret
        $secret = new Secret;
        $secret->slug = substr(Str::slug(Str::random(48)), 0, 32);
        $secret->secret = encrypt($request->secret);
        $secret->expires_at = $request->expires ? Carbon::parse($request->expires) : null;
        $secret->save();

        // Display the secret url
        return view('stored', compact('secret'));
    }

    /**
     * Show the user the secret landing page.
     *
     * @param  string  $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Secret $secret)
    {
        // Display the landing page
        return view('show', compact('secret'));
    }

    /**
     * Show and destroy the secret.
     *
     * @param  \App\Models\Secret  $secret
     *
     * @return string
     */
    public function reveal(Secret $secret)
    {
        // Delete the secret
        // $secret->delete();

        // Display the secret
        return view('reveal', compact('secret'));
    }

    /**
     * Destroy the secret without showing it.
     *
     * @param  \App\Models\Secret  $secret
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Secret $secret)
    {
        // Delete the secret.
        $secret->delete();

        // Show it to the user.
        return view('destroyed');
    }
}
