<?php

namespace LaravelWorkcast\RegistrationEndpoints;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class Register extends AbstractEndpoint
{
    protected string $eventPak = '';

    protected array $eventSessions = [];

    protected string $contactTitle = '';

    protected string $contactFirstName = '';

    protected string $contactLastName = '';

    protected string $contactEmail = '';

    protected string $contactPhone = '';

    protected string $contactJobTitle = '';

    protected string $contactCompany = '';

    protected string $contactAddressLine1 = '';

    protected string $contactAddressLine2 = '';

    protected string $contactAddressLine3 = '';

    protected string $contactCity = '';

    protected string $contactCountyOrState = '';

    protected string $contactPostcode = '';

    protected string $contactCountryCode = '';

    protected array $contactCustomItems = [];

    public function eventPak(string $eventPak): static
    {
        $this->eventPak = Str::limit($eventPak, 16, '');

        return $this;
    }

    public function addEventSession(string $sessionPak): static
    {
        $sessionPak = Str::limit($sessionPak, 16, '');
        if (!in_array($sessionPak, $this->eventSessions)) {
            $this->eventSessions[] = $sessionPak;
        }

        return $this;
    }

    public function contactTitle(string $contactTitle): static
    {
        $this->contactTitle = Str::limit($contactTitle, 20, '');

        return $this;
    }

    public function contactFirstName(string $contactFirstName): static
    {
        $this->contactFirstName = Str::limit($contactFirstName, 50, '');

        return $this;
    }

    public function contactLastName(string $contactLastName): static
    {
        $this->contactLastName = Str::limit($contactLastName, 20, '');

        return $this;
    }

    public function contactEmail(string $contactEmail): static
    {
        $this->contactEmail = Str::limit($contactEmail, 100, '');

        return $this;
    }

    public function contactPhone(string $contactPhone): static
    {
        $this->contactPhone = Str::limit($contactPhone, 20, '');

        return $this;
    }

    public function contactJobTitle(string $contactJobTitle): static
    {
        $this->contactJobTitle = Str::limit($contactJobTitle, 50, '');

        return $this;
    }

    public function contactCompany(string $contactCompany): static
    {
        $this->contactCompany = Str::limit($contactCompany, 50, '');

        return $this;
    }

    public function contactAddressLine1(string $contactAddressLine1): static
    {
        $this->contactAddressLine1 = Str::limit($contactAddressLine1, 30, '');

        return $this;
    }

    public function contactAddressLine2(string $contactAddressLine2): static
    {
        $this->contactAddressLine2 = Str::limit($contactAddressLine2, 30, '');

        return $this;
    }

    public function contactAddressLine3(string $contactAddressLine3): static
    {
        $this->contactAddressLine3 = Str::limit($contactAddressLine3, 30, '');

        return $this;
    }

    public function contactCity(string $contactCity): static
    {
        $this->contactCity = Str::limit($contactCity, 20, '');

        return $this;
    }

    public function contactCountyOrState(string $contactCountyOrState): static
    {
        $this->contactCountyOrState =  Str::limit($contactCountyOrState, 20, '');

        return $this;
    }

    public function contactPostcode(string $contactPostcode): static
    {
        $this->contactPostcode = Str::limit($contactPostcode, 20, '');

        return $this;
    }

    public function contactCountryCode(string $contactCountryCode): static
    {
        $this->contactCountryCode = Str::limit($contactCountryCode, 5, '');

        return $this;
    }

    public function addContactContactCustomItem(string $key, mixed $value): static
    {
        $this->contactCustomItems[$key] = $value;

        return $this;
    }

    protected function body(): array
    {
        $customItems = [];
        foreach ($this->contactCustomItems as $key => $value) {
            $customItems[] = [
                'itemDesc'  => $key,
                'itemValue' => $value,
            ];
        }

        $sessions = [];
        foreach ($this->eventSessions as $pak) {
            $sessions[] = [
                'sessionPak' => $pak,
            ];
        }

        return [
            'registration' => [
                'event' => [
                    'eventPak' => $this->eventPak,
                    'sessions' => $sessions,
                ],
                'contact' => [
                    'title'              => $this->contactTitle,
                    'firstName'          => $this->contactFirstName,
                    'lastName'           => $this->contactLastName,
                    'email'              => $this->contactEmail,
                    'phone'              => $this->contactPhone,
                    'jobTitle'           => $this->contactJobTitle,
                    'company'            => $this->contactCompany,
                    'addressLine1'       => $this->contactAddressLine1,
                    'addressLine2'       => $this->contactAddressLine2,
                    'addressLine3'       => $this->contactAddressLine3,
                    'city'               => $this->contactCity,
                    'countyOrState'      => $this->contactCountyOrState,
                    'postCode'           => $this->contactPostcode,
                    'countryCode'        => $this->contactCountryCode,
                    'contactCustomItems' => $customItems,
                ],
            ],
        ];
    }

    public function send(): Response
    {
        return $this->httpClient()->put('register', $this->body());
    }
}
