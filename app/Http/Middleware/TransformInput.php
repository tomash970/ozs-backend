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
        $newFileSet = [];
        // if ($request->file() !== []) {
        //     $file = $request->file();
        //     foreach ($file as $input => $value) {
        //        $transformedInput[$transformer::originalAttribute($input)] = $value;
        //     }
        // }
        //$altRequest = $request;
//dd($request);
        $allFiles = $request->allFiles(); 
// dd($request);
         $allFields = $request->all();
//dd($request);
        

//dd($request);        
        //$queryParams = $request->files;
//dd($allFiles);
        //$transformableFields = array_diff($allFields, $queryParams);
       // if ($request->file() !== []) {
       //      $file = $request->files;
       //      foreach ($file as $input => $value) {
       //         $transformedInput[$transformer::originalAttribute($input)] = $value;
       //      }
       //  }


        foreach ($allFiles as $input => $value) {

            $newFileSet[$transformer::originalAttribute($input)] = $value;
        }
//dd($request);

        foreach ($allFields as $input => $value) {

            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

//dd($newFileSet);
        // foreach ($request->request->all() as $input => $value) {
        //    $transformedInput[$transformer::originalAttribute($input)] = $value;
        // }

        //dd($request);

        

//dd($transformedInput);
        $request->replace($transformedInput);
        $request->files->replace($newFileSet);
        //$request->convertedFiles->replace($newFileSet);
        //$altRequest->replace($request);
dd($request);
//return $next($request);
        $response = $next($request);
//dd($response);
        //transformation on transformed attribute

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
