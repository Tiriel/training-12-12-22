<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    private ?string $name;

    /**
     * @Assert\Email(mode="strict")
     * @Assert\NotBlank()
     */
    private ?string $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    private ?string $subject;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=20)
     */
    private ?string $message;

    public function __construct(string $name = null, string $email = null, string $subject = null, string $message = null)
    {

        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function setName(?string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function setSubject(?string $subject): Contact
    {
        $this->subject = $subject;
        return $this;
    }

    public function setMessage(?string $message): Contact
    {
        $this->message = $message;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}