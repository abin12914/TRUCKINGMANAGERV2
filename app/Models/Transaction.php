<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Transaction extends Model
{
	//landlord
    use BelongsToTenants;
}
