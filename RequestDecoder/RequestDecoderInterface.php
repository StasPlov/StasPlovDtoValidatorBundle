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

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 * 
 * [RFC-2616] — GET uri, POST body https://www.rfc-editor.org/rfc/rfc2616#section-4.3
 */
interface RequestDecoderInterface {

	/**
     * Decode Request params
     *
     * @param Request $request
     * @param array $arrayKey array type param key name
	 * 
     * @return array
     */
	public function decode(Request $request): array;


 	/**
	 * Decode Request params for DTO object class
	 * 
	 * @param Request $request
	 * @param string $dto
	 * 
	 * @return object
  	 */
	public function decodeDto(Request $request, string $dto): object;
}