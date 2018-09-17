<?php

namespace App\Traits;

trait BaseModelTimezones
{
    /**
     * Display timestamps in user's timezone
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Carbon
     */
    protected function asDateTime($value)
    {
        if ($user = auth()->user()) {
            return (parent::asDateTime($value))->timezone($user->timezone);
        }

        if ($guestTimezone = request()->cookie('guest-timezone')) {
            return (parent::asDateTime($value))->timezone($guestTimezone);
        }

        return parent::asDateTime($value);
    }

    /**
     * Convert a DateTime to a storable string.
     *
     * @param  \DateTime|int  $value
     * @return string
     */
    public function fromDateTime($value)
    {
        // ensure that if the datetime or carbon object we were given contained a timezone, we convert to the app timezone (instead of stripping it) before outputting a storable string
        if ($value) {
            return $this->asDateTime($value)->setTimeZone(config('app.timezone'))->format($this->getDateFormat());
        }
        return $value;
    }
}
