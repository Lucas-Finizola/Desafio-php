<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chamado extends Model
{
    protected $fillable = [
        'user_id',
        'tecnico_id',
        'titulo',
        'descricao',
        'categoria',
        'prioridade',
        'status',
        'anexo',
    ];

    protected $appends = ['anexo_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class)->latest();
    }

    public function getAnexoUrlAttribute()
    {
        return $this->anexo ? asset('storage/'.$this->anexo) : null;
    }
}