<?php

namespace App\Http\Controllers\Api;

use App\Agency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Job;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use MediaUploader;

class FilesController extends Controller
{

    public function __construct()
    {
        $this->middleware( "auth:api" );
    }

    /**
     * Upload and attach a new file.
     * @param Request $request
     * @param string $folder
     * @return
     */
    public function create( Request $request, $folder = 'jobs' )
    {

        $userFilePath = \Auth::guard('api')->user()->filePath( $folder );
        $media = MediaUploader::fromSource( $request->file( 'file' ))
                ->useHashForFilename()
                ->toDirectory($userFilePath)
                ->upload();

        if ($media) {
            return response()->json( [
                'message' => "File uploaded successfully.",
                'status'  => "success",
                'path'    => $media->getDiskPath(),
                'user_path' => $userFilePath,
                'url' => $media->getUrl(),
                'id' => $media->getKey()
            ] );
        }

        return response()->json( [
            'message' => "There was an error uploading the file, please try again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Upload and attach a new file.
     * @param Request $request
     * @param string $folder
     * @return
     */
    public function avatar( Request $request )
    {

        $userFilePath = \Auth::guard('api')->user()->filePath( "avatar" );
        $media = MediaUploader::fromSource( $request->file( 'img' ) )
            ->useHashForFilename()
            ->toDirectory( $userFilePath )
            ->upload();

        if ( $media ) {

            /* Attach to user */
            \Auth::guard('api')->user()->syncMediaExtra( $media->getKey(), "avatar", [
                'name' => "Avatar",
                'description' => "User avatar.",
                'public' => true,
                'featured' => true
            ]);

            return response()->json( [
                'message'   => "File uploaded successfully.",
                'status'    => "success",
                'path'      => $media->getDiskPath(),
                'user_path' => $userFilePath,
                'url'       => $media->getUrl(),
                'id'        => $media->getKey()
            ] );
        }

        return response()->json( [
            'message' => "There was an error uploading the file, please try again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    /**
     * Upload and attach a new file.
     * @param Request $request
     * @param string $folder
     * @return
     */
    public function agencyAvatar( Request $request, Agency $agency )
    {

        if (!$agency) {
            return response()->json( [
                'message' => "There was an error uploading the file, please try again.",
                'status'  => "error"
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $userFilePath = $agency->filePath( "avatar" );
        $media = MediaUploader::fromSource( $request->file( 'img' ) )
            ->useHashForFilename()
            ->toDirectory( $userFilePath )
            ->upload();

        if ( $media ) {

            /* Attach to agency */
            $agency->syncMediaExtra( $media->getKey(), "avatar", [
                'name'        => "Avatar",
                'description' => "Agency avatar.",
                'public'      => true,
                'featured'    => true
            ] );

            return response()->json( [
                'message'   => "File uploaded successfully.",
                'status'    => "success",
                'path'      => $media->getDiskPath(),
                'user_path' => $userFilePath,
                'url'       => $media->getUrl(),
                'id'        => $media->getKey()
            ] );
        }

        return response()->json( [
            'message' => "There was an error uploading the file, please try again.",
            'status'  => "error"
        ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }


}
