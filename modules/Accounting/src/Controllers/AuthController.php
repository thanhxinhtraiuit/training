<?php

namespace Accounting\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Core\Controllers\BaseController as Controller;
use Core\Models\BaseModel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Accounting\Jobs\UserJob;

use App\Models\User;
use Accounting\Models\User as modelUser;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {

        try {
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'status' => BaseModel::CODE_ERROR,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();

        $this->dispatchJob(new UserJob(['user_id' => $user->id]), 'Accouting-user');
        
        return response()->json([
            'status' => 1,
            'message' => 'Successfully created user!'
        ], 200);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|string',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'status' => BaseModel::CODE_ERROR,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Email or password incorrect!'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'status'  => 1,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {

        $user_id = Auth::user()->id;

        $user = modelUser::where('id', $user_id)->with('role.permissions')->with('permissions')->first()->toArray();

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $user,
        ], Response::HTTP_OK);
    }
}
