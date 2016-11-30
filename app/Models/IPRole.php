<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class IPRole extends Model
{
    protected $table = 'ip_roles';

    protected $fillable = [
        'role_id',
        'ip_start',
        'ip_end',
        'created_by',
        'updated_by',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public static function checkIP($ip)
    {
        $ips     =  self::getIPsWithRoles();
        $ipRoles =  $ips->where('ip',$ip)->all();
        
        if ($ipRoles) {
            foreach ($ipRoles as $ipRole) {
                if (Auth::user()->hasRole($ipRole->role->name))
                    return true;
            }
            return false;
        } else {
            return true;
        }

    }

    private static function getIPsWithRoles()
    {
        $ipRoles = IPRole::with('role')->get();
        $ips     = collect();

        foreach ($ipRoles as $ipRole) {
            $ipFirst = ip2long($ipRole->ip_start);
            $ipLast  = ip2long($ipRole->ip_end);
            for ($x= $ipFirst;$x<=$ipLast;$x++) {
                $ips->push((object)['ip'=>long2ip($x),'role'=>$ipRole->role]);
            }
        }        
        
        return $ips;
    }
}

/*create table ip_roles (
  id int(10) unsigned not null auto_increment,
  role_id int(11) not null,
  ip_start varchar(64) default null,
  ip_end varchar(64) default null,
  created_by int(4) not null,
  updated_by int(4) not null,
  created_at datetime not null,
  updated_at datetime not null,
  primary key (id)
);
*/