<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Member extends Model
{
    use HasFactory, BelongsToTenant;

    protected $primaryKey = 'MemberID';
    public $timestamps = false;
}
