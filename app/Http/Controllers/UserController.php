<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:8'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user) return response()->json(['message'=> 'User Not Found'], 404);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user) return response()->json(['message'=> 'User Not Found'], 404);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users,email,'.$id,
            'password'=>'required|string|min:8'
        ]);
        // return response()->json($user,200);
        if(isset($validatedData['password'])){
            $validatedData['password'] = bcrypt($validatedData['password']);
        }
        $user->update($validatedData);
        return response()->json($user,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(!$user) return response()->json(['message'=> 'User Not Found'], 404);

        $user->delete();
        return response()->json(null,204);
    }
}
