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
 * Adds CORS headers and handles pre-flight requests
 *
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
class KernelListener {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	private RequestDecoderInterface $requestDecoder;

	private ValidatorInterface $validator;

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
			$validator = new Validation();
		}

		$this->logger = $logger;
		$this->requestDecoder = $requestDecoder;
		$this->validator = $validator;
	}

	/**
	 * Kernel Controller function
	 *
	 * @param ControllerEvent $event
	 * @return void
	 */
	public function onKernelController(ControllerEvent $event) {
		if (!$event->isMainRequest()) {
			return;
		}

		$controller = $event->getController();
		$request = $event->getRequest();
		

		if (is_array($controller)) {
			/**
			 * @var array<callable>
			 */
			$controller = $controller[0];
		}
		
		$explodeController = explode('::', $request->get('_controller'));
		$requestMethodName = $explodeController[
			count($explodeController) - 1
		];

		if($requestMethodName == 'error_controller') { // exit for base exception
			return;
		}
	
		$reflectionObject = new ReflectionObject((object)$controller);
		$reflectionMethod = $reflectionObject->getMethod($requestMethodName);
		
		$attribute = $this->attributeHandler($reflectionMethod, ValidateDto::class);

		if(!isset($attribute)) { // if not validation attribute
			return;
		}

		$args = $attribute->getArguments();
		
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
			static function() use ($reflectionMethod, $controller, $parameterName, $data) {
				return $reflectionMethod->invokeArgs((object)$controller, [$parameterName => $data]);
			}
		);
	}

	protected function validate(object $data) {
		if(!$data) {
            throw new ValidateException('Invalid JSON format');
        }

        $errors = $this->validator->validate($data);

        if(count($errors) > 0) {
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
			//dd($method);
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
	 * Summary of attributeHandler
	 * 
	 * @param ReflectionMethod $method
	 * @param string $annotationClass
	 * 
	 * @return ReflectionAttribute
	 */
	protected function attributeHandler(ReflectionMethod $method, string $annotationClass): ?ReflectionAttribute  {
		/** 
		 * @var ArrayCollection<ReflectionAttribute>
		 */ 
		$result = (new ArrayCollection($method->getAttributes()))->filter(fn($i) => $i->getName() === $annotationClass);
		
		if($result->isEmpty()) {
			return null;
		}

		return $result[0];
	}
}