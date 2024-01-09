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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionAttribute;
use ReflectionMethod;
use ReflectionObject;
use StasPlov\DtoValidatorBundle\Annotation\ValidateDto;
use StasPlov\DtoValidatorBundle\Exception\ValidateException;
use StasPlov\DtoValidatorBundle\RequestDecoder\RequestDecoder;
use StasPlov\DtoValidatorBundle\RequestDecoder\RequestDecoderInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * Add Validate for DTO Object fron controller method
 *
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
class ControllerListener
{
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
	 * @param ControllerEvent $event
	 * 
	 * @return void
	 */
	public function onKernelController(ControllerEvent $event) {
		if (!$event->isMainRequest()) {
			return;
		}

		/**
		 * @var callable $controller
		 */
		$controller = (is_array($event->getController()) ? ((array)$event->getController())[0] : $event->getController());
		$request = $event->getRequest();

		$explodeController = explode('::', $request->get('_controller'));
		$requestMethodName = $explodeController[
			count($explodeController) - 1
		];

		if ($requestMethodName == 'error_controller') { // exit for base exception
			return;
		}

		$reflectionObject = new ReflectionObject((object) $controller);
		$reflectionMethod = $reflectionObject->getMethod($requestMethodName);

		$attribute = $this->attributeHandler($reflectionMethod, ValidateDto::class);

		if (!isset($attribute)) { // if not validation attribute
			return;
		}

		$args = $attribute->getArguments(); #some need check empty args

		/**
		 * @var string
		 */
		$parameterName = $args['data'];

		/**
		 * @var object
		 */
		$data = $this->requestDecoder->decodeDto($request, $args['class']);

		$this->validate($data); // validate

		$event->setController(
			static function () use ($reflectionMethod, $controller, $parameterName, $data) {
				return $reflectionMethod->invokeArgs((object) $controller, [$parameterName => $data]);
			}
		);
	}

	/**
	 * Validate json data
	 *
	 * @param object $data
	 * 
	 * @return void
	 */
	protected function validate(object $data): void {
		if (!$data) {
			throw new ValidateException('Invalid JSON format');
		}

		$errors = $this->validator->validate($data);

		if (count($errors) > 0) {
			$errorMessages = [];

			foreach ($errors as $error) {
				$errorMessages[] = [
					'property' => $error->getPropertyPath(),
					'message' => $error->getMessage()
				];
			}

			throw new ValidateException(json_encode(['errors' => $errorMessages]));
		}
	}

	/**
	 * Get Method Parameter List
	 * 
	 * @param ReflectionMethod $method
	 * 
	 * @return array
	 */
	protected function getMethodParameterList(ReflectionMethod $method): array {
		$result = [];

		foreach ($method->getParameters() as $parameter) {
			if (!array_key_exists($parameter->getType()->getName(), $result)) {
				//$result = [...$result, $parameter->getName()];
				$result = [
					...$result,
					$parameter->getName() => $parameter->getType()->getName()
				];
			}
		}

		return $result;
	}

	/**
	 * Get ValidateDto attribute from method
	 *
	 * @param ReflectionMethod $method
	 * @param string $annotationClass
	 * 
	 * @return ReflectionAttribute|null
	 */
	protected function attributeHandler(ReflectionMethod $method, string $annotationClass): ?ReflectionAttribute {
		/** 
		 * @var array<ReflectionAttribute>
		 */
		$result = array_filter(
			$method->getAttributes(),
			static fn($i) => $i->getName() === $annotationClass
		);

		if ($result === []) {
			return null;
		}

		return $result[0];
	}
}