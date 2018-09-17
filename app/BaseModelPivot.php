<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\BaseModelTimezones;

class BaseModelPivot extends Pivot
{
    use BaseModelTimezones;
}
