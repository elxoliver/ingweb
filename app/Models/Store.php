<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store';
    protected $primaryKey = 'store_id';

    public function staff_members()
    {
        return $this->hasMany('App\Models\Staff', 'store_id');
    }

    public function manager_staff()
    {
        $this->belongsTo('App\Models\Staff', 'manager_staff');
    }
}
