# How to Use

The core idea of StasPlovDtoValidatorBundle is to validate input data in the controller from the Request
using so-called DTO (Data Transfer Object) entities.


```
#[ValidateDto(data: 'createDto', class: CreateDto::class)]
#[Route(path: '/create/user', name: 'api-user-create', methods: ['POST'])]
public function createUser(CreateDto $createDto): Response {
	// ... some code
}
```

The `$createDto` variable will contain all the data described in the corresponding `CreateDto` class.

# DTO

A DTO object represents a class that describes the fields of permissible input data.

* DTO objects actively support the use of `symfony/validator`.

`StasPlovDtoValidatorBundle` provides the ability to create your own DTO objects by extending DtoAbstract.

Example:



```
<?php

namespace App\Dto\User;

use StasPlov\DtoValidatorBundle\Service\Dto\DtoAbstract;
use StasPlov\DtoValidatorBundle\Service\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Validator;
use Symfony\Component\Validator\Constraints\Email;

class CreateDto extends DtoAbstract implements DtoInterface {

	#[Validator\Uuid]
	#[Validator\Length(min: 36, max: 36)]
	private string $id;
	
	#[Validator\NotBlank]
	#[Validator\NotNull]
	#[Validator\Length(min: 1, max: 180)]
	private string $username;

	#[Validator\NotBlank]
	#[Validator\NotNull]
	#[Validator\Email(mode: Email::VALIDATION_MODE_STRICT)]
	#[Validator\Length(min: 1, max: 255)]
	private string $mail;

	#[Validator\NotBlank]
	#[Validator\NotNull]
	#[Validator\Length(min: 1, max: 255)]
	private string $password;

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}
	
	/**
	 * @param string $username 
	 * @return self
	 */
	public function setUsername(string $username): self {
		$this->username = $username;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getMail(): string {
		return $this->mail;
	}
	
	/**
	 * @param string $mail 
	 * @return self
	 */
	public function setMail(string $mail): self {
		$this->mail = $mail;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPassword(): string {
		return $this->password;
	}
	
	/**
	 * @param string $password 
	 * @return self
	 */
	public function setPassword(string $password): self {
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getId(): ?string {
		try {
			return $this->id;
		} catch (\Throwable $th) {
			return null;
		}
	}
	
	/**
	 * @param string $id 
	 * @return self
	 */
	public function setId(string $id): self {
		$this->id = $id;
		return $this;
	}
}
```