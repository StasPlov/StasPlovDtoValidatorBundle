<?php

/*
 * This file is part of the StasPlovDtoValidatorBundle.
 *
 * (c) Stas Plov <SaviouR.S@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StasPlov\DtoValidatorBundle\RequestDecoder;

use StasPlov\DtoValidatorBundle\Service\Dto\DtoAbstract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 * 
 * [RFC-2616] — GET uri, POST body https://www.rfc-editor.org/rfc/rfc2616#section-4.3
 */
final class RequestDecoder implements RequestDecoderInterface {

	protected const DECODE_FORMAT = 'json';

    /**
     * Decode request for RFC-2616 standart
     *
     * @param Request $request
     * @return array
     */
	public function decode(Request $request): array {
		$params = [];

		if($request->getMethod() === Request::METHOD_GET) {
			$params = $request->query->all();
		}

        if($request->getMethod() === Request::METHOD_POST) {
            $params = $request->toArray();
        }

        return $params;
	}

	/**
	 * Decode request for RFC-2616 standart
	 *
	 * @param Request $request
	 * @param class-string<DtoAbstract> $dto
	 * 
	 * @return object
	 */
	public function decodeDto(Request $request, string $dto): object {
		$params = $this->decode($request);

		$serializer = new Serializer([
				new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, new ReflectionExtractor()), 
				new ArrayDenormalizer()
			], [
				new JsonEncoder()
			]
		);

		return $serializer->deserialize(json_encode($params), $dto, self::DECODE_FORMAT);
	}	
}