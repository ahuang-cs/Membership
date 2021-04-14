<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant
{
  use HasDomains;

//    protected $primaryKey = 'ID';
//    public $timestamps = false;

    public static function getCustomColumns(): array
  {
    return [
      'ID',
      'Name',
      'Code',
      'Website',
      'Email',
      'Verified',
      'UniqueID',
    ];
  }

  public function getIncrementing()
  {
    return true;
  }

    public function getTenantKeyName(): string
    {
        return 'ID';
    }
}
