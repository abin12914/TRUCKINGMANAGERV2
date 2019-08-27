<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Torzer\Awesome\Landlord\BelongsToTenants;

class Voucher extends Model
{
    //landlord
    use BelongsToTenants;
}
