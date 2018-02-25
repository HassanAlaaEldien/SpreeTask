<?php

namespace App\Http\Controllers;

use App\Http\Responses\ResponsesInterface;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTController extends Controller
{
    protected $responder;

    /**
     * JWTController constructor.
     * @param ResponsesInterface $responder
     */
    public function __construct(ResponsesInterface $responder)
    {
        $this->middleware('auth:api', ['except' => 'authenticate']);
        $this->responder = $responder;
    }

    /**
     * Handles login with jwtAuth mechanism
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {

        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->responder->respondAuthenticationError('Wrong credentials!');
            }
        } catch (JWTException $e) {
            return $this->responder->respondInternalError('Could not create a token');
        }

        return $this->responder->respond(compact('token'));
    }

    /**
     * Logs a user out with jwt mechanism
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if ($token = JWTAuth::invalidate(JWTAuth::getToken())) {
            return $this->responder->respond(['message' => 'logged out']);
        }
        return $this->responder->respondWithError('Could not log you out, please try again!');
    }
}
