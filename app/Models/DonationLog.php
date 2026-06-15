<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationLog extends Model
{
    protected $fillable = ['donation_campaign_id', 'donor_name', 'donor_email', 'donor_phone', 'amount', 'bank_name', 'proof_of_payment', 'status', 'notes'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DonationCampaign::class, 'donation_campaign_id');
    }
}
