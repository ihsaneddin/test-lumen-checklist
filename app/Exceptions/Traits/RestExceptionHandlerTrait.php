<?php

namespace App\Exceptions\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Auth\AuthenticationException;

use Prettus\Validator\Exceptions\ValidatorException as ValidationError;

trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        $retval = null;
        switch(true) {
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound();
                break;
            case $this->isQueryErrorException($e) :
                $retval = $this->modelNotFound();
                break;
            case $this->isValidationErrorException($e) :
                $retval = $this->validationError($e->getMessageBag());
                break;
            case $this->isTooManyRequest($e):
                $message = empty($e->getMessage()) ? "You have reached request limit" : $e->getMessage();
                $retval = $this->jsonResponse(array('error' => $message), 401);
              break;
            case $this->isAuthenticationException($e):
                $message = $e->getMessage();
                $retval = $this->jsonResponse(array('error' => $message), 401);
                break;
            default:
                $message = empty($e->getMessage()) ? "Route not found" : $e->getMessage();
                //$retval = $this->badRequest($message);
                $retval = null;
                break;
        }
        //return $retval;
        return is_null($retval) ? $this->badRequest() : $retval;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message='Bad request', $statusCode=400)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message='Record not found', $statusCode=404)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    protected function validationError(MessageBag $messageBag, $message = "Unprocessable entity",  $statusCode=422){
      return $this->JsonResponse(["error" => $message, "details" => $messageBag->messages()], $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload=null, $statusCode=404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isQueryErrorException(Exception $e){
      return $e instanceOf QueryException;
    }

    protected function isValidationErrorException(Exception $e){
      return $e instanceOf ValidationError;
    }

    protected function isTooManyRequest(Exception $e){
      return $e instanceOf TooManyRequestsHttpException;
    }

    protected function isAuthenticationException(Exception $e){
        return $e instanceOf AuthenticationException;
    }

}
