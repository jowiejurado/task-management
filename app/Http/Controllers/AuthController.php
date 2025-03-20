<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$request->validate([
			'email' 		=> 'required|email',
			'password' 	=> 'required',
		]);

		$credentials = $request->only('email', 'password');

		if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
			$request->offsetSet('email', $request->email);
			$credentials = $request->only('email', 'password');
		}

		if (Auth::attempt($credentials)) {
			$user = request()->user();
			$user->tokens()->delete();

			return $this->respondWithToken($request);
		}

		return response()->json([
			'message' => 'Credentials are incorrect',
		], 401);
	}

	protected function respondWithToken(Request $request)
	{
		$user = $request->user();

		$client = $user->createToken('Authentication Token');

		return response()->json([
			'token' 			=> $client->plainTextToken,
			'token_type' 	=> 'Bearer',
		]);
	}

	public function logout(Request $request)
	{
		$user = User::find(Auth::id());

		$user->tokens()->delete();

		return response([], 204);
	}

	public function register(Request $request)
	{
		$request->validate([
			'first_name' 		=> 'required|string',
			'last_name' 		=> 'required|string',
			'email' 				=> 'required|email|unique:users,email',
			'password' 			=> 'required|string|min:6',
		]);

		$user = new User();
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->save();

		return response()->json([
			'message' => 'User created successfully',
		], 201);
	}
}
