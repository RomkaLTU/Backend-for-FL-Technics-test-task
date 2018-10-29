<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\AddEvent;
use App\Http\Requests\RegisterUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register( RegisterUser $request )
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['email'],
            'name' => $validated['fullname'],
            'password' => Hash::make($validated['password']),
        ]);

        return $user;
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if ( !$token = auth('api')->attempt($credentials) ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'expires' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function addevent( AddEvent $request )
    {
        $validated = $request->validated();

        $data = array_add( $validated, 'user_id', Auth::id() );

        $event = Event::create($data);

        return $event;
    }

    public function getevents()
    {
        return Event::where('user_id',Auth::id())->get()->groupBy('date');
    }

    public function complete( Event $event )
    {
        $event->update([
            'status' => 1
        ]);

        return $this->getevents();
    }

    public function delete( Event $event )
    {
        $event->delete();

        return $this->getevents();
    }
}
