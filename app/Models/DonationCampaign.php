<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCampaign extends Model
{
    protected $fillable = ['title', 'slug', 'image', 'description', 'target_amount', 'current_amount', 'status', 'end_date'];

    public function logs(): HasMany
    {
        return $this->hasMany(DonationLog::class);
    }

    /**
     * Helper: Menghitung persentase dana terkumpul secara dinamis untuk Progress Bar
     */
    public function getPercentageAttribute(): int
    {
        if ($this->target_amount <= 0) return 0;
        $percent = ($this->current_amount / $this->target_amount) * 100;
        return $percent > 100 ? 100 : (int) $percent;
    }
}
