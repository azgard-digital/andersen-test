<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'wallets.from' => 'required|string|max:32|min:32',
            'amount' => 'required|integer',
            'wallets.to' => 'required|string|min:32|max:32|different:wallets.from|exists:wallets,address',
        ];
    }
}
