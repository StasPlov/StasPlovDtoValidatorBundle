<?php

/*
 * This file is part of the StasPlovDtoValidatorBundle.
 *
 * (c) Stas Plov <SaviouR.S@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StasPlov\DtoValidatorBundle\Service\Dto;
use Symfony\Component\Validator\Constraints as Validator;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 * 
 */
abstract class DtoAbstract implements DtoInterface {

	#[Validator\Type('int')]
	#[Validator\Positive()]
	public int $_limit = 25;

	#[Validator\Type('int')]
	#[Validator\PositiveOrZero()]
	public int $_offset = 0;

	#[Validator\Type('string')]
	#[Validator\Regex(
		pattern: DtoPattern::REGEX_TEXT_WITH_DOTS_NO_SPACES, 
		message: 'The string can only contain letters, spaces, and periods.'
	)]
	public string $_sortBy = '';

	#[Validator\Type('string')]
	#[Validator\Regex(
		pattern: DtoPattern::REGEX_TEXT_WITH_DOTS_NO_SPACES, 
		message: 'The string can only contain letters, spaces, and periods.'
	)]
	public string $_orderBy = '';

	#[Validator\Type('bool')]
	#[Validator\PositiveOrZero()]
	public bool $_count = false;

	#[Validator\Type('bool')]
	#[Validator\PositiveOrZero()]
	public bool $_nolimit = false;

	/**
	 * Get the value of _limit
	 *
	 * @return int
	 */
	public function get_limit(): int
	{
		return $this->_limit;
	}

	/**
	 * Set the value of _limit
	 *
	 * @param int $_limit
	 *
	 * @return self
	 */
	public function set_limit(int $_limit): self
	{
		$this->_limit = $_limit;

		return $this;
	}

	/**
	 * Get the value of _offset
	 *
	 * @return int
	 */
	public function get_offset(): int
	{
		return $this->_offset;
	}

	/**
	 * Set the value of _offset
	 *
	 * @param int $_offset
	 *
	 * @return self
	 */
	public function set_offset(int $_offset): self
	{
		$this->_offset = $_offset;

		return $this;
	}

	/**
	 * Get the value of _sortBy
	 *
	 * @return string
	 */
	public function get_sortBy(): string
	{
		return $this->_sortBy;
	}

	/**
	 * Set the value of _sortBy
	 *
	 * @param string $_sortBy
	 *
	 * @return self
	 */
	public function set_sortBy(string $_sortBy): self
	{
		$this->_sortBy = $_sortBy;

		return $this;
	}

	/**
	 * Get the value of _orderBy
	 *
	 * @return string
	 */
	public function get_orderBy(): string
	{
		return $this->_orderBy;
	}

	/**
	 * Set the value of _orderBy
	 *
	 * @param string $_orderBy
	 *
	 * @return self
	 */
	public function set_orderBy(string $_orderBy): self
	{
		$this->_orderBy = $_orderBy;

		return $this;
	}

	/**
	 * Get the value of _count
	 *
	 * @return bool
	 */
	public function get_count(): bool
	{
		return $this->_count;
	}

	/**
	 * Set the value of _count
	 *
	 * @param bool $_count
	 *
	 * @return self
	 */
	public function set_count(bool $_count): self
	{
		$this->_count = $_count;

		return $this;
	}

	/**
	 * Get the value of _nolimit
	 *
	 * @return bool
	 */
	public function get_nolimit(): bool
	{
		return $this->_nolimit;
	}

	/**
	 * Set the value of _nolimit
	 *
	 * @param bool $_nolimit
	 *
	 * @return self
	 */
	public function set_nolimit(bool $_nolimit): self
	{
		$this->_nolimit = $_nolimit;

		return $this;
	}

	/**
	 * Unique array. Remove dublicate
	 *
	 * @param array|null $arr
	 * 
	 * @return array|null
	 */
	public static function uniqueArray(?array $arr): ?array {
		if($arr === null) {
			return null;
		}

		return array_map(
			"unserialize", 
			array_unique(
				array_map("serialize", $arr)
			)
		);
	}
}
