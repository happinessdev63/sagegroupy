<?php

namespace App\Http\Controllers\Api;

use App\Agency;
use App\MailLog;
use App\Reference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Job;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MailLogsController extends Controller
{

    public function __construct()
    {
        $this->middleware( "auth:api" )->except("fetch");
    }

    /**
     * Return mail logs for admin management.
     * @param Request $request ['filter','search','sort','pager','page']
     * @return mixed
     */
    public function index( Request $request )
    {
        $query = MailLog::with( "sender","receiver","job","agency" );

        /* Handle custom sorting on relations */
        if ( $request->has( "sort" ) ) {

            switch ($request->sort['name']) {

                case "type":
                case "status":
                case "created_at":
                    $query->orderBy( $request->sort['name'], $request->sort['type'] );
                    break;
                case "sender":
                    $query->leftJoin( 'users', 'users.id', '=', 'sender_id' )
                        ->orderBy( 'users.name', $request->sort['type'] )
                        ->select( "mail_logs.*" );

                    break;
                case "receiver":
                    $query->leftJoin( 'users', 'users.id', '=', 'to_user_id' )
                        ->orderBy( 'users.name', $request->sort['type'] )
                        ->select( "mail_logs.*" );

                    break;
            }

        } else {
            $query->orderBy( "created_at", "desc" );
        }



        if ( $request->has( "pager" ) ) {
            $page = $request->pager['page'];
            $size = $request->pager['size'];
        } else {
            $page = 1;
            $size = 25;
        }

        if ( $request->has( "search" ) && !empty( $request->search ) ) {
            $query->where( "type", "like", "%" . $request->search . "%" );
        }

        if ($request->has('receiver_id') && !empty($request->user_id)) {
            $query->orWhere(function($query) use($request) {
                $query->where( "receiver_id", $request->user_id );
            });

        }

        if ( $request->has( "filter" ) && !empty( $request->filter ) ) {
        }

        return $query->paginate( $size );
    }

}
