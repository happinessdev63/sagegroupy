<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = ['id'];
    protected $appends = [ 'avg_rates', 'suggestions'];

    const HIGH_RATE_WARN_THRESHOLD = "1.3";
    const LOW_RATE_WARN_THRESHOLD = "0.7";
    const LOW_USER_COUNT_THRESHOLD = "4";

    public function users() {
        return $this->morphedByMany(User::class, "skillable","skill_user")->withPivot( "level", "experience", "rate", "notes" );
    }

    public function averageRateByLevel($level = "Entry", $excludeUserId = false) {
        $avgRates = $this->getAvgRatesAttribute($excludeUserId);

        foreach ($avgRates as $rateLevel) {
            if ($rateLevel['level'] == $level) {
                return $rateLevel['rate'];
            }
        }
    }

    public function getAvgRatesAttribute($excludeUserId = false) {

        $cacheId = $this->id . "avg_skill_rates";
        if (\Cache::has($cacheId)) {
            return \Cache::get($cacheId);
        }

        $levels = [ 'Entry', 'Junior', 'Intermediate', 'Senior', 'Expert' ];

        $avgRates = collect();
        foreach ($levels as $level) {

            if ($excludeUserId != false) {
                $userSkills = $this->users()->where("id","!=", $excludeUserId)->wherePivot( 'level', $level )->get();
            } else {
                $userSkills = $this->users()->wherePivot( 'level', $level )->get();
            }

            $pivotData = collect();
            $userSkills->each(function ($item) use (&$pivotData) {
                $pivotData->push($item->pivot);
            });

            $avgRates->push([
                'level' => $level,
                'rate'  =>  number_format(round($pivotData->avg("rate"),2),2)
                ]
            );
        }

        $avgRates->push([
            'level' => 'Total',
            'rate'  => number_format( round($avgRates->avg("rate"),2),2)
        ]);

        \Cache::put($cacheId, $avgRates, 24 * 60);

        return $avgRates;
    }

    public function getSuggestionsAttribute( )
    {
        if (!isset($this->relations['pivot']) || !\Auth::user()) {
            return [];
        }

        $cacheId = $this->id . "skill_suggestions";
        if ( \Cache::has( $cacheId ) ) {
            return \Cache::get( $cacheId );
        }

        $avgRate = $this->averageRateByLevel( $this->pivot->level, \Auth::user()->id );
        $rate = $this->pivot->rate;

        $suggestions = $this->skillSuggestions($rate, $avgRate);

        \Cache::put( $cacheId, $suggestions, 24 * 60 );

        return $suggestions;
    }

    public function skillSuggestions($rate, $avgRate ) {
        $numUsers = max( 0, $this->users()->count() );
        $suggestions = [];
        $settings = new \App\Services\Settings();

        if ( $numUsers == 0 ) {
            $suggestion = $settings->get( "suggestion_no_users" );
            if ( !empty( $suggestion ) ) {
                $suggestions[] = $suggestion;
            } else {
                $suggestions[] = "No other freelancers have selected this skill yet. You may be able to raise your rates. ";
            }

            return $suggestions;
        }

        if ( $numUsers <= $settings->get("low_user_count_threshold", self::LOW_USER_COUNT_THRESHOLD) ) {
            $suggestion = $settings->get( "suggestion_low_user_count" );
            $suggestion = str_replace( "[NUM_USERS]", $numUsers, $suggestion );
            if ( !empty( $suggestion ) ) {
                $suggestions[] = $suggestion;
            }
            return $suggestions;
        }

        if ( $avgRate > 0 && ( $rate / $avgRate ) >= $settings->get( "high_rate_threshold", self::HIGH_RATE_WARN_THRESHOLD) ) {
            $howMuchHigher = round( ( $rate / $avgRate ) * 100, 2 );
            $suggestion = $settings->get( "suggestion_high_rate");
            $suggestion = str_replace("[PERCENTAGE]", $howMuchHigher, $suggestion);
            if (!empty($suggestion)) {
                $suggestions[] = $suggestion;
            }
        }

        if ( $avgRate > 0 && ( $rate / $avgRate ) <= $settings->get( "low_rate_threshold", self::LOW_RATE_WARN_THRESHOLD) ) {
            $howMuchHigher = round( ( 1 - ( $rate / $avgRate ) ) * 100, 2 );
            $suggestion = $settings->get( "suggestion_low_rate" );
            $suggestion = str_replace( "[PERCENTAGE]", $howMuchHigher, $suggestion );
            if ( !empty( $suggestion ) ) {
                $suggestions[] = $suggestion;
            }
        }

        return $suggestions;
    }

}
