<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'color', 'sort_order', 'is_active', 'description'];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'category_id');
    }
}
