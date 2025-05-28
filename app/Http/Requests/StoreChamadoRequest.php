<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChamadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria' => 'required|string|max:100',
            'prioridade' => 'required|in:Baixa,Média,Alta',
            'anexo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'mensagem' => 'required|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'titulo.required' => 'O título do chamado é obrigatório',
            'descricao.required' => 'A descrição do chamado é obrigatória',
            'prioridade.in' => 'A prioridade deve ser Baixa, Média ou Alta',
            'anexo.mimes' => 'O anexo deve ser uma imagem (JPG, PNG), PDF ou documento do Word',
            'anexo.max' => 'O tamanho máximo do anexo é 2MB',
            'mensagem.required' => 'A mensagem da resposta é obrigatória',
            'mensagem.max' => 'A mensagem não pode ter mais que 2000 caracteres',
        ];
    }
}