<?php

namespace App\Services;

use App\Models\LegalCase;

class BillingService
{
    public function getAppearanceFee(LegalCase $case): float
    {
        if ($case->relationLoaded('courtDates')) {
            $trialDatesCount = $case->courtDates->where('type', 'trial_date')->count();
        } else {
            $trialDatesCount = $case->courtDates()->where('type', 'trial_date')->count();
        }
        
        $rate = $case->assignedAttorney->flat_appearance_rate ?? 0.0;
        return $trialDatesCount * (float) $rate;
    }

    public function getTrustBalance(LegalCase $case): float
    {
        if ($case->relationLoaded('ledgerEntries')) {
            return (float) $case->ledgerEntries->where('type', 'trust')->sum('amount');
        }
        return (float) $case->ledgerEntries()->where('type', 'trust')->sum('amount');
    }

    public function getOperationalBalance(LegalCase $case): float
    {
        if ($case->relationLoaded('ledgerEntries')) {
            return (float) $case->ledgerEntries->where('type', 'operational')->sum('amount');
        }
        return (float) $case->ledgerEntries()->where('type', 'operational')->sum('amount');
    }
}
