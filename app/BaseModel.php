<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Traits\BaseModelTimezones;

class BaseModel extends Eloquent
{
    use BaseModelTimezones;
}
