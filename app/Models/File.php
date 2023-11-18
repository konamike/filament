<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Contractor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class File extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'contractor_id',
        'file_number',
        'category_id',
        'received_by',
        'date_received',
        'document_author',
        'document_sender',
        'amount',
        'description',
        'email',
        'hand_carried',
        'retrieved_by',
        'date_retrieved',
        'treated',
        'date_treated',
        'treated_by',
        'user_id',
        'notes',
        'remarks',
        'date_dispatched',
        'sent_from',
        'sent_to',
        'dispatch_phone',
        'dispatch_email',
        'dispatched_by',
        'dispatch_remarks',
        'dispatched',
    ];


    protected $casts = [
        'date_received' => 'date',
        'date_retrieved' => 'date',
        'date_treated' => 'date',
        'date_dispatched' => 'date',
        'treated' => 'boolean',
        'dispatched' => 'boolean'
    ];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['description', 'contractor_id', 'amount', 'treated', 'treated_by', 'dispatched', 'dispatch_note'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

