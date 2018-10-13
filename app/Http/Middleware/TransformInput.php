<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
         $transformedInput = [];

        foreach ($request->request->all() as $input => $value) {
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformedInput);

        $response = $next($request);
//dd($response);
        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();
//dd($data->error);
            $transformedErrors = [];

            foreach ($data->error as $field => $error) {
                $transformedField = $transformer::transformedAttribute($field);

                $transformedErrors[$transformedField] = str_replace(str_replace('_', ' ',$field), $transformedField, $error);

           }


           $data->error = $transformedErrors;
//dd($data->error);
           $response->setData($data);

        }

        return $response;
    }
}
