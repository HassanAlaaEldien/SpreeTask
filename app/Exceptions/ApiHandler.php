<?php

namespace App\Exceptions;

use App\Http\Responses\Responder;
use App\Http\Responses\ResponsesInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiHandler extends Handler
{
    /**
     * @var ResponsesInterface
     */
    private $apiResponder;

    /**
     * ApiHandler constructor.
     *
     * @param Container $container
     * @param ResponsesInterface $apiResponder
     */
    public function __construct(Container $container, ResponsesInterface $apiResponder)
    {
        parent::__construct($container);
        $this->apiResponder = $apiResponder;
    }

    /**
     * Prepare exception for rendering.
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException)) {
            return $this->apiResponder->respondNotFound();
        } elseif ($exception instanceof AuthorizationException) {
            return $this->apiResponder->respondAuthorizationError();
        }

        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param \Illuminate\Validation\ValidationException $e
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->response?? $e->validator->errors()->getMessages();

        return $this->apiResponder->respondWithValidationError($errors);
    }

    /**
     * Converts an authenticated exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->apiResponder->respondAuthenticationError();
    }
}