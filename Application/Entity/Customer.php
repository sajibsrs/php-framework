<?php

namespace Application\Entity;

class Customer extends Base
{
    const TABLE = 'customers';

    protected string $name = '';
    protected float $balance = 0.0;
    protected string $email = '';
    protected string $password = '';
    protected int $status = 0;
    protected string $securityQuestion = '';
    protected int $profileId = 0;
    protected string $confirmCode = '';
    protected string $level = '';

    /**
     * Map database rows to fields
     * 
     * @var array
     */
    protected array $mapping = [
        'id'                => 'id',
        'name'              => 'name',
        'balance'           => 'balance',
        'email'             => 'email',
        'password'          => 'password',
        'status'            => 'status',
        'security_question' => 'securityQuestion',
        'confirm_code'      => 'confirmCode',
        'profile_id'        => 'profileId',
        'level'             => 'level'
    ];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setSecurityQuestion(string $question)
    {
        $this->securityQuestion = $question;
    }

    public function getSecurityQuestion(): string
    {
        return $this->securityQuestion;
    }

    public function setProfileId(int $id)
    {
        $this->profileId = $id;
    }

    public function getProfileId(): int
    {
        return $this->profileId;
    }

    public function setConfirmCode(string $code)
    {
        $this->confirmCode = $code;
    }

    public function getConfirmCode(): string
    {
        return $this->confirmCode;
    }

    public function setLevel(string $level)
    {
        $this->level = $level;
    }

    public function getLevel(): string
    {
        return $this->level;
    }
}
