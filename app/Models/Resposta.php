<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resposta extends Model
{
    protected $fillable = [
        'chamado_id',
        'user_id',
        'mensagem'
    ];

    public function chamado(): BelongsTo
    {
        return $this->belongsTo(Chamado::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}