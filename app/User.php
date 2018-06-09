<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use MediableSage;
    use Notifiable;

    const TIME_BETWEEN_REMINDERS = 604800;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Attributes that should be appendend when converted to array or JSON
     * @var array
     */
    protected $appends = [
        'is_admin',
        'is_client',
        'is_freelancer',
        'first_name',
        'profile_link',
        'profile_edit_link',
        'public_profile_url',
        'nice_date',
        'nice_role',
        'avatar',
        'files',
        'percent_complete_score',
        'recent_views'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'meta' => 'array'
    ];


    public function clientJobs()
    {
        return $this->hasMany( Job::class, 'client_id' );
    }

    public function freelancerJobs()
    {
        return $this->hasMany( Job::class, 'freelancer_id' );
    }

    public function generalReferences()
    {
        return $this->hasMany( GeneralReference::class, 'user_id' );
    }

    public function agencies()
    {
        return $this->belongsToMany( Agency::class )->with('owner');
    }

    public function ownedAgencies()
    {
        return $this->hasMany( Agency::class, "owner_id" )->with( 'owner', 'freelancers' );
    }

    public function notifications()
    {
        return $this->hasMany( Notification::class, 'to_user_id' );
    }

    public function recommendedUsers()
    {
        return $this->hasMany( Recommendation::class, 'from_user_id' );
    }

    public function recommendations()
    {
        return $this->hasMany( Recommendation::class, 'to_user_id' );
    }

    public function references()
    {
        return $this->hasMany( Reference::class, 'freelancer_id' );
    }

    public function links()
    {
        return $this->hasMany( Link::class );
    }

    public function views()
    {
        return $this->hasMany( View::class );
    }

    public function invites()
    {
        return $this->hasMany( Invite::class );
    }

    public function skills()
    {
        return $this->morphToMany( Skill::class, "skillable","skill_user" )->with("users")->withPivot( "level","experience","rate","notes");
    }

    public function minimalSkills()
    {
        return $this->morphToMany( Skill::class, "skillable", "skill_user" )->withPivot( "level", "experience", "rate", "notes" );
    }

    public function isAdmin(  )
    {
        return $this->getIsAdminAttribute();
    }

    public function filePath($folder = "")
    {
        $path = md5($this->id);
        if (!empty($folder)) {
            $path .= "/" . $folder;
        }

        return $path;
    }

    /** Group views by last 14 days */
    public function getRecentViewsAttribute(  )
    {
        $startDate = Carbon::now()->subWeeks(12)->toDateString();
        $views = \App\View::where('created_at',">=", $startDate)
            ->where('user_id', $this->id)
            ->groupBy( 'date' )
            ->orderBy( 'date', 'ASC' )
            ->get( array(
                \DB::raw( 'Date(created_at) as date' ),
                \DB::raw( 'COUNT(*) as "views"' )
            ));

        $stats = [];
        foreach ($views as $day) {
            $stats[$day['date']] = max(1,$day['views']);
        }

        return [
            'stats' => $stats,
            'count' => count($stats)
        ];

    }


    public function getPercentCompleteScoreAttribute() {
        return min(100, round($this->complete_score / 5 * 100));
    }

    public function getTotalJobsAttribute() {
        return $this->clientJobs->count() + $this->freelancerJobs->count();
    }

    public function getIsAdminAttribute()
    {
        return $this->role == "admin";
    }

    public function getIsClientAttribute()
    {
        return $this->role == "client" || $this->role == "client_freelancer";
    }

    public function getIsFreelancerAttribute()
    {
        return $this->role == "freelancer" || $this->role == "client_freelancer";
    }

    public function getFirstNameAttribute()
    {
        $nameParts = explode( " ", $this->name );
        if ( isset( $nameParts[0] ) ) {
            return $nameParts[0];
        }

        return "";
    }

    public function getNiceDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getProfileLinkAttribute()
    {
        return "/profile/" . $this->id;
    }

    public function getProfileEditLinkAttribute()
    {
        return "/profile/edit/" . $this->id;
    }

    public function getNameAttribute( $value )
    {
        return ucwords( $value );
    }

    public function getAvatarAttribute(  )
    {
        if ($this->hasMedia('avatar')) {
            return $this->firstMedia('avatar')->getUrl();
        }

        return '/img/avatar.jpg';

    }

    public function getNiceRoleAttribute()
    {
        return ucfirst( str_replace( "_", " & ", $this->role ) );
    }

    public function getPublicProfileUrlAttribute()
    {
        return env( 'APP_URL' ) . "/p/" . \Hashids::encode($this->id);
    }

    public function setMeta($field, $value) {
        $userMeta = $this->meta;
        $userMeta[$field] = $value;
        $this->meta = $userMeta;
    }

    public function getFilesAttribute()
    {
        $mediaItems = $this->getMedia( "files" );

        $files = [];
        foreach ($mediaItems as $media) {
            $files[] = [
                'name'        => $media->pivot->name,
                'description' => $media->pivot->description,
                'public'      => (bool) $media->pivot->public,
                'id'          => $media->id,
                'path'        => $media->getDiskPath(),
                'url'         => $media->getUrl(),
                'is_image'    => $media->aggregate_type == "image"
            ];
        }

        return $files;

    }

    public function getPublicMediaAttribute()
    {
        $mediaItems = $this->media;

        $files = [];
        foreach ($mediaItems as $media) {
            if ($media->pivot->public) {
                $files[] = $media;
            }
        }

        return $files;

    }

    public function getActiveModalsAttribute(  )
    {
        $modal = '';

        /* Don't show modals if user is newly created */
        if (strtotime($this->created_at) >= time() - self::TIME_BETWEEN_REMINDERS) {
            return $modal;
        }

        if ($this->complete_score <= 40
            && (!isset($this->meta['modal_profile_reminder']) || $this->meta['modal_profile_reminder'] <= time() - self::TIME_BETWEEN_REMINDERS) )
        {
            $modal = 'profileReminder';
            $this->setMeta( 'modal_profile_reminder', time());
            $this->save();
            return $modal;
        }

        if ( $this->getIsFreelancerAttribute()
            && $this->freelancerJobs()->where('type','reference')->count() <= 0
            && ( !isset( $this->meta['modal_references_reminder'] ) || $this->meta['modal_references_reminder'] <= time() - self::TIME_BETWEEN_REMINDERS )
        ) {
            $modal = 'referencesReminder';
            $this->setMeta( 'modal_references_reminder', time() );
            $this->save();
            return $modal;
        }

        if ( $this->getIsClientAttribute()
            && $this->clientJobs()->count() <= 0
            && ( !isset( $this->meta['modal_jobs_reminder'] ) || $this->meta['modal_jobs_reminder'] <= time() - self::TIME_BETWEEN_REMINDERS )
        ) {
            $modal = 'jobsReminder';
            $this->setMeta( 'modal_jobs_reminder', time() );
            $this->save();
            return $modal;
        }

        if ( $this->getIsFreelancerAttribute()
            && $this->agencies()->count() <= 0
            && $this->ownedAgencies()->count() <= 0
            && ( !isset( $this->meta['modal_agencies_reminder'] ) || $this->meta['modal_agencies_reminder'] <= time() - self::TIME_BETWEEN_REMINDERS )
        ) {
            $modal = 'agenciesReminder';
            $this->setMeta( 'modal_agencies_reminder', time() );
            $this->save();
            return $modal;
        }

        return $modal;


    }

    public function updateScore(  )
    {

        $user = $this;
        $score = 0;
        $completeScore = 0;

        if ( $user->getAvatarAttribute() != '/img/avatar.jpg' ) {
            $score++;
            $completeScore++;
        }

        if ( strlen( $user->bio ) >= 100 ) {
            $score++;
            $completeScore++;
        }

        if ( $user->tagline != 'No tagline set.' ) {
            $score++;
            $completeScore++;
        }

        if ( strlen( $user->company ) > 0 ) {
            $score++;
            $completeScore++;
        }

        if ( strlen( $user->company_bio ) > 0 ) {
            $score++;
            $completeScore++;
        }

        if ( strlen( $user->phone ) > 0 ) {
            $score++;
            $completeScore++;
        }

        if ( $user->skills()->count() > 0 ) {
            $score += $user->skills()->count();
            $completeScore++;
        }

        if ( $user->freelancerJobs()->count() > 0 ) {
            $score += $user->freelancerJobs()->count();
        }

        if ( $user->agencies()->count() > 0 ) {
            $score += $user->agencies()->count();
        }

        $user->update(
            [
                'search_score'   => $score,
                'complete_score' => $completeScore
            ]
        );

    }
}
