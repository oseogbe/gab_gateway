<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Make a request to the register route in the users microservice
        $response = Http::acceptJson()->post(config('hosts.users') . '/register', $request->all());

        // Check the response status code
        if ($response->status() === 201) {
            // Registration successful, return the user details
            return $response->json();
        } else {
            // Registration failed, return an error response with the retrieved message
            return response()->json([
                'message' => $response->json('message'),
            ], $response->status());
        }
    }

    public function login(Request $request)
    {
        // Make a request to the login route in the users microservice
        $response = Http::acceptJson()->post(config('hosts.users') . '/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Check the response status code
        if ($response->status() === 200) {
            // Login successful, return the user info and token
            return $response->json();
        } else {
            // Login failed, return an error response
            return response()->json([
                'message' => $response->json('message'),
            ], $response->status());
        }
    }

    public function logout(Request $request)
    {
        // Make a request to the logout route in the users microservice
        $response = Http::withToken($request->bearerToken())
                        ->acceptJson()
                        ->post(config('hosts.users') . '/logout');

        // Check the response status code
        if ($response->status() === 200) {
            // Logout successful
            return $response->json();
        } else {
            // Logout failed, return an error response
            return response()->json([
                'message' => $response->json('message'),
            ], $response->status());
        }
    }
}
