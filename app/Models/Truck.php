<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Truck extends Model
{
	//landlord
    use BelongsToTenants;
}
