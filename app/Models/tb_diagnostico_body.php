<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_diagnostico_body extends Model
{
    use HasFactory;

    protected $table = "tb_diagnostico_body";
    public $timestamps = true;

    protected $fillable = [
        'id_diagnostico_header_fk',
        'id_pergunta_fk',
        'id_resposta_fk',
    ];

    public $messages = [
        'id_diagnostico_header_fk.required' => 'O campo UNIDADE é obrigatório!',
        'id_pergunta_fk.required' => 'O campo PERGUNTA é obrigatório!',
        'id_resposta_fk.required' => 'O campo RESPOSTO é obrigatório!',

    ];

    public $rules =  [
        'id_diagnostico_header_fk'=> 'required',
        'id_pergunta_fk' => 'required',
        'id_resposta_fk' => 'required',

    ];

}
