<?php

/*
 * This file is part of the StasPlovDtoValidatorBundle.
 *
 * (c) Stas Plov <SaviouR.S@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StasPlov\DtoValidatorBundle\EventListener;

use ReflectionClass;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use StasPlov\DtoValidatorBundle\Annotation\ValidateDto;
use StasPlov\DtoValidatorBundle\Exception\ValidateException;
use StasPlov\DtoValidatorBundle\RequestDecoder\RequestDecoder;
use StasPlov\DtoValidatorBundle\RequestDecoder\RequestDecoderInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Add Validate for DTO Object fron controller method
 *
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
class ControllerListener {
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var RequestDecoderInterface
	 */
	private $requestDecoder;

	/**
	 * @var ValidatorInterface
	 */
	private $validator;

	private const CONTROLLER_ERROR = 'error_controller';
	private const REQUEST_CONTROLLER_FIELD = '_controller';

	public function __construct(
		?LoggerInterface $logger = null,
		?RequestDecoderInterface $requestDecoder = null,
		?ValidatorInterface $validator = null,
	) {
		if ($logger === null) {
			$logger = new NullLogger();
		}

		if ($requestDecoder === null) {
			$requestDecoder = new RequestDecoder();
		}

		if ($validator === null) {
			$validator = validation::createValidator();
		}

		$this->logger = $logger;
		$this->requestDecoder = $requestDecoder;
		$this->validator = $validator;
	}

	/**
	 * Kernel Controller function
	 *
	 * @param ControllerArgumentsEvent $event
	 * 
	 * @return void
	 */
	public function onKernelController(ControllerArgumentsEvent $event) {
		if (!$event->isMainRequest()) {
			return;
		}

		$request = $event->getRequest();
		$methodArgs = $event->getNamedArguments();

		# if not set some args return
		if ($methodArgs === []) {
			return;
		}

		$explodeController = explode('::', $request->get(self::REQUEST_CONTROLLER_FIELD));
		$requestMethodName = $explodeController[count($explodeController) - 1];

		# exit for base exception
		if ($requestMethodName === self::CONTROLLER_ERROR) {
			return;
		}

		/**
		 * @var ValidateDto|null $attribute
		 */
		$attribute = $event->getAttributes()[ValidateDto::class][0] ?? null;

		# if haven`t attribute
		if ($attribute === null) {
			return;
		}

		$parameterDtoClass = $attribute->getClass();
		$parameterName = (new ReflectionClass($parameterDtoClass))->getShortName();

		# decode dto
		$data = $this->requestDecoder->decodeDto($request, $parameterDtoClass);

		# validate dto
		$this->validate($data); 

		foreach ($methodArgs as $key => $item) {
			if (strcasecmp($key, $parameterName) === 0) {
				$methodArgs[$key] = $data;
			}
		}

		$event->setArguments(
			$methodArgs
		);
	}

	/**
	 * Validate json data
	 *
	 * @param object $data
	 * 
	 * @return void
	 */
	private function validate(object $data): void {
		if (!$data) {
			throw new ValidateException(
				json_encode(['errors' => 'Invalid data format'])
			);
		}

		$errors = $this->validator->validate($data);

		if ($errors->count()) {
			$errorMessages = [];

			foreach ($errors as $error) {
				$errorMessages[] = [
					'property' => $error->getPropertyPath(),
					'message' => $error->getMessage()
				];
			}

			throw new ValidateException(
				json_encode(['errors' => $errorMessages])
			);
		}
	}
}