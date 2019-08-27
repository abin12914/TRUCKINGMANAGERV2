<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

class EmployeeWage extends Model
{
	//landlord
    use BelongsToTenants;
}
