<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Responder implements ResponsesInterface
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the status code according to a passed int
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Respond with a validation error
     *
     * @param $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithValidationError($errors)
    {
        return $this->setStatusCode(422)->respondWithError($errors);
    }

    /**
     * Respond with a not found error
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * Respond with an internal server error
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Server Error!')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Respond with authorization error
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondAuthorizationError($message = 'You don\'t have the rights to access this resource.')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    /**
     * Respond with authentication error
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondAuthenticationError($message = 'Forbidden!')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * Respond with general error
     *
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        return $this->prepareJsonResponse([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * Respond with a message showing that the desired resource has been deleted successfully
     *
     * @param string $resourceName
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithResourceDeletedSuccessfully(string $resourceName)
    {
        return $this->prepareJsonResponse([
            'status' => 'success',
            'message' => "{$resourceName} has been deleted successfully"
        ]);
    }

    /**
     * General response
     *
     * @param $data
     * @param string $responseType
     * @param string $responseTo
     * @param string $dataRedirectType
     * @param array $headers
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function respond($data, $responseType = '', $responseTo = '', $dataRedirectType = '', $headers = [])
    {
        return strpos(request()->route()->getPrefix(), 'api') !== false
            ? $this->prepareJsonResponse($data, $headers)
            : $this->prepareResponse($data, $responseType, $responseTo, $dataRedirectType);
    }

    /**
     * Json response.
     *
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function prepareJsonResponse($data, $headers = [])
    {
        return \Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Web response.
     *
     * @param array $data
     * @param string $responseType
     * @param string $responseTo
     * @param string $dataRedirectType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function prepareResponse($data = [], $responseType = 'view', $responseTo = '/', $dataRedirectType = 'default')
    {
        switch ($responseType) {
            case 'view':
                return $dataRedirectType !== 'session' ? view($responseTo, $data) : view($responseTo)->with($data);
                break;
            case 'redirect':
                return $dataRedirectType !== 'session' ? redirect()->route($responseTo, $data) : redirect()->route($responseTo)->with($data);
                break;
            case 'redirectBack':
                return redirect()->back()->with($data);
                break;
            default:
                return redirect()->route('home');
        }
    }
}