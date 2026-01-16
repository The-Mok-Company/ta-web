<?php

namespace App\Enums;

enum InquiryStatus: string
{
    case New = 'new';
    case Pending = 'pending';
    case Responded = 'responded';
    case OfferSent = 'offer_sent';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case DealClosed = 'deal_closed';
    case Cancelled = 'cancelled';
    case OnHold = 'on_hold';
    case Expired = 'expired';

    public function label(): string
    {
        return match($this) {
            self::New => translate('New'),
            self::Pending => translate('Pending'),
            self::Responded => translate('Responded'),
            self::OfferSent => translate('Offer Sent'),
            self::Accepted => translate('Accepted'),
            self::Rejected => translate('Rejected'),
            self::DealClosed => translate('Deal Closed'),
            self::Cancelled => translate('Cancelled'),
            self::OnHold => translate('On Hold'),
            self::Expired => translate('Expired'),
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::New => 'badge-info',
            self::Pending => 'badge-warning',
            self::Responded => 'badge-primary',
            self::OfferSent => 'badge-info',
            self::Accepted => 'badge-success',
            self::Rejected => 'badge-danger',
            self::DealClosed => 'badge-success',
            self::Cancelled => 'badge-danger',
            self::OnHold => 'badge-warning',
            self::Expired => 'badge-secondary',
        };
    }
}
