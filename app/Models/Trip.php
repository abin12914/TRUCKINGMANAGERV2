<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Trip extends Model
{
	//landlord
    use BelongsToTenants;
}
