<?php

/*
 * This file is part of the StasPlovDtoValidatorBundle.
 *
 * (c) Stas Plov <SaviouR.S@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StasPlov\DtoValidatorBundle\Annotation;

use Attribute;
use Symfony\Component\DependencyInjection\Attribute\Target;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 * 
 * @Annotation
 * @Target({"METHOD"})
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ValidateDto {
	public string $data;
    public string $class;

	public function __construct(
		$data = '',
		$class = ''
	) {
		$this->data = $data;
		$this->class = $class;
	}
}