<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComposerController extends Controller
{
    public function composerRequireWhatsapp()
    {
        // Check if the user is authenticated and has the right permissions
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Execute the composer require command for the package
        $command = 'composer require missael-anda/laravel-whatsapp';
        $output = shell_exec($command . ' 2>&1'); // Run the command and capture the output (including errors)

        // Return the output in the response
        return response()->json(['output' => $output]);
    }
}
