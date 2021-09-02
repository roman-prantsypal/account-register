<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\CreateClientModel;
use App\Http\Requests\Contracts\CreateUserModel;
use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest implements CreateClientModel, CreateUserModel
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'name' => [
                'required',
                'string',
            ],
            'address1' => [
                'required',
                'string',
            ],
            'address2' => [
                'required',
                'string',
            ],
            'city' => [
                'required',
                'string',
                'max:100',
            ],
            'state' => [
                'required',
                'string',
                'max:100',
            ],
            'country' => [
                'required',
                'string',
                'max:100',
            ],
            'zipCode' => [
                'required',
                'int',
            ],
            'phoneNo1' => [
                'required',
                'string',
                'max:20',
            ],
            'phoneNo2' => [
                'string',
                'max:20',
                "nullable",
            ],
            'user.firstName' => [
                'required',
                'string',
                'max:50',
            ],
            'user.lastName' => [
                'required',
                'string',
                'max:50',
            ],
            'user.email' => [
                'required',
                'string',
                'unique:users,email',
                'email',
                'max:150',
            ],
            'user.password' => [
                'required',
                'string',
                'min:6',
                'max:256',
                'required_with:user.passwordConfirmation',
                'same:user.passwordConfirmation',
            ],
            'user.phone' => [
                'required',
                'string',
                'max:20',
            ],
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getAddress1(): string
    {
        return $this->input('address1');
    }

    public function getAddress2(): string
    {
        return $this->input('address2');
    }

    public function getCity(): string
    {
        return $this->input('city');
    }

    public function getState(): string
    {
        return $this->input('state');
    }

    public function getCountry(): string
    {
        return $this->input('country');
    }

    public function getZipCode(): string
    {
        return $this->input('zipCode');
    }

    public function getPhoneNo1(): string
    {
        return $this->input('phoneNo1');
    }

    public function getPhoneNo2(): string
    {
        return $this->input('phoneNo2') ?? '';
    }

    public function getFirstName(): string
    {
        return $this->input('user.firstName');
    }

    public function getLastName(): string
    {
        return $this->input('user.lastName');
    }

    public function getEmail(): string
    {
        return $this->input('user.email');
    }

    public function getPassword(): string
    {
        return bcrypt($this->input('user.password'));
    }

    public function getPhone(): string
    {
        return $this->input('user.phone');
    }
}
