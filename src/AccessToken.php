<?php

namespace LaravelWorkcast;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class AccessToken
{
    protected string $token;

    protected \DateTimeInterface $expiresAt;

    protected int $expiresIn = 0;

    /**
     * AccessToken constructor.
     *
     * @param string $token
     * @param int $expiresIn
     * @param \DateTimeInterface|null $expiresAt
     */
    public function __construct(string $token, int $expiresIn, ?\DateTimeInterface $expiresAt = null)
    {
        $this->token     = $token;
        $this->expiresIn = $expiresIn;

        if ($expiresAt) {
            $this->expiresAt = $expiresAt;
        } else {
            $this->expiresAt = Carbon::now()->addSeconds($this->expiresIn);
        }
    }

    /**
     * Check is access token valid
     * @return bool
     */
    public function valid(): bool
    {
        return $this->token && Carbon::now()->addMinute()->lessThan($this->expiresAt);
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        if ($this->valid()) {
            return $this->expiresAt;
        }

        return Carbon::now();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Get Authorization header value
     *
     * @return string|null
     */
    public function getAuthorizationHeader(): ?string
    {
        if ($this->valid()) {
            return "Bearer {$this->token}";
        }

        return null;
    }

    public function __serialize(): array
    {
        return [
            'token'     => $this->token,
            'expiresIn' => $this->expiresIn,
            'expiresAt' => $this->expiresAt->format('Y-m-d H:i:s'),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->token     = $data['token']     ?? '';
        $this->expiresIn = $data['expiresIn'] ?? 0;

        try {
            $this->expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $data['expiresAt']);
        } catch (\Exception $e) {
            $this->expiresAt = Carbon::now();
        }
    }

    public function getRawData(array|string $only = [
        'expiresIn',
        'expiresAt',
    ]): array
    {
        return collect([
            'token'     => $this->token,
            'expiresIn' => $this->expiresIn,
            'expiresAt' => $this->expiresAt->format('Y-m-d H:i:s'),
        ])
            ->only(Arr::wrap($only))
            ->toArray();
    }
}
