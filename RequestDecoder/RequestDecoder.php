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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Stas Plov <SaviouR.S@email.ru>
 * 
 * [RFC-2616] — GET uri, POST body https://www.rfc-editor.org/rfc/rfc2616#section-4.3
 */
class RequestDecoder implements RequestDecoderInterface {

    /**
     * Decode request for RFC-2616 standart
     *
     * @param Request $request
     * @return array
     */
	private function decode(Request $request): array {
		$params = [];

		if($request->getMethod() == Request::METHOD_GET) {
			$params = $request->query->all();
		}

        if($request->getMethod() == Request::METHOD_POST) {
            $params = $request->toArray();
        }

        return $params;
	}

	/**
	 * Decode request for RFC-2616 standart
	 *
	 * @param Request $request
	 * @param string $dto
	 * 
	 * @return object
	 */
	public function decodeDto(Request $request, string $dto): object {
		$params = $this->decode($request);

		$serializer = new Serializer(
			[new ObjectNormalizer()], 
			[new JsonEncoder()]
		);

		return $serializer->deserialize(json_encode($params), $dto, 'json');
	}
}