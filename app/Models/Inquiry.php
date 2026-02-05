<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Inquiry extends Model
{
    use HasFactory;

    /**
     * Available inquiry statuses
     */
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_RESPONDED = 'responded';
    const STATUS_OFFER_SENT = 'offer_sent';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DEAL_CLOSED = 'deal_closed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_RESPONDED => 'Responded',
            self::STATUS_OFFER_SENT => 'Offer Sent',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_DEAL_CLOSED => 'Deal Closed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_ON_HOLD => 'On Hold',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    /**
     * Get status color class for display
     */
    public static function getStatusColor(string $status): string
    {
        return match($status) {
            self::STATUS_NEW => 'primary',
            self::STATUS_PENDING => 'warning',
            self::STATUS_RESPONDED => 'info',
            self::STATUS_OFFER_SENT => 'secondary',
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_DEAL_CLOSED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_ON_HOLD => 'warning',
            self::STATUS_EXPIRED => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Days before inquiry expires (auto-expiry after 1 month)
     */
    const EXPIRY_DAYS = 30;

    protected $fillable = [
        'code',
        'user_id',
        'admin_id',
        'note',
        'user_note',
        'status',
        'products_total',
        'categories_total',
        'subtotal',
        'tax',
        'delivery',
        'discount',
        'extra_fees',
        'total',
    ];

    protected $casts = [
        'products_total'   => 'decimal:2',
        'categories_total' => 'decimal:2',
        'subtotal'         => 'decimal:2',
        'tax'              => 'decimal:2',
        'delivery'         => 'decimal:2',
        'discount'         => 'decimal:2',
        'extra_fees'       => 'decimal:2',
        'total'            => 'decimal:2',
    ];

    /**
     * Auto-generate inquiry code AFTER record is created
     * Example: INQ-000123
     */
    protected static function booted()
    {
        static::created(function (Inquiry $inquiry) {

            if (empty($inquiry->code)) {
                $inquiry->updateQuietly([
                    'code' => 'INQ-' . str_pad($inquiry->id, 6, '0', STR_PAD_LEFT),
                ]);
            }

            if (empty($inquiry->status)) {
                $inquiry->updateQuietly([
                    'status' => self::STATUS_NEW,
                ]);
            }
        });
    }

    /**
     * Check if inquiry is expired (older than 30 days and not closed/cancelled)
     */
    public function isExpired(): bool
    {
        $nonExpirableStatuses = [
            self::STATUS_DEAL_CLOSED,
            self::STATUS_CANCELLED,
            self::STATUS_EXPIRED,
            self::STATUS_ACCEPTED,
        ];

        if (in_array($this->status, $nonExpirableStatuses)) {
            return false;
        }

        return $this->created_at->diffInDays(Carbon::now()) >= self::EXPIRY_DAYS;
    }

    /**
     * Scope: filter by type (product or category inquiries)
     */
    public function scopeOfType($query, string $type)
    {
        return $query->whereHas('items', function ($q) use ($type) {
            $q->where('type', $type);
        });
    }

    /**
     * Scope: filter expired inquiries
     */
    public function scopeExpired($query)
    {
        $nonExpirableStatuses = [
            self::STATUS_DEAL_CLOSED,
            self::STATUS_CANCELLED,
            self::STATUS_EXPIRED,
            self::STATUS_ACCEPTED,
        ];

        return $query->whereNotIn('status', $nonExpirableStatuses)
            ->where('created_at', '<=', Carbon::now()->subDays(self::EXPIRY_DAYS));
    }

    /* ================= Relations ================= */

    public function items()
    {
        return $this->hasMany(InquiryItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function notes()
    {
        return $this->hasMany(InquiryNote::class)->orderBy('created_at', 'asc');
    }
}
