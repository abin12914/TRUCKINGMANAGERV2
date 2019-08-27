<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Sale extends Model
{
	//landlord
    use BelongsToTenants;
}
