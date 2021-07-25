<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Exception;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdminToken
{

    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception  $e) {
            if($e instanceof TokenInvalidException){
                return  $this->returnError(2222,'Invalid Token');
            }else if($e instanceof TokenExpiredException){
                return  $this->returnError(2222,'Expired Token');

            }else{
                return  $this->returnError(2222,'Token Not Found');
            }
        } catch (Throwable $e) {
            if($e instanceof TokenInvalidException){
                return  $this->returnError(2222,'Invalid Token');
            }else if($e instanceof TokenExpiredException){
                return  $this->returnError(2222,'Expired Token');

            }else{
                return  $this->returnError(2222,'Token Not Found');
            }

        }

        if(!$user){
            return  $this->returnError(2222,'Unauthenticate');
        }
        return $next($request);
    }
}
