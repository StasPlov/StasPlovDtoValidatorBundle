<?php

namespace StasPlov\DtoValidatorBundle\Service\Dto;

use Symfony\Component\Validator\Constraints as Validator;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 * 
 */
class DtoPattern
{
	/**
	 * Pattern for text only, validating letters and spaces.
	 * 
	 * Шаблон только для текста, валидирует буквы и пробелы.
	 */
	public const REGEX_TEXT_ONLY = '/^[\pL\s]+$/u';

	/**
	 * Pattern for text with dots, validating letters, spaces, and periods.
	 * 
	 * Шаблон текста с точками, валидирует буквы, пробелы и точки.
	 */
	public const REGEX_TEXT_WITH_DOTS = '/^[\pL\s.]+$/u';

	/**
	 * Pattern for validating text that contains only letters and periods,
	 * and does not contain spaces or empty input.
	 * 
	 * Шаблон для проверки текста, который содержит только буквы и точки,
	 * и не содержит пробелов или пустого ввода.
	 */
	public const REGEX_TEXT_WITH_DOTS_NO_SPACES = '/^[\pL.]+$/u';


	/**
	 * Pattern for alphanumeric strings, no special characters.
	 * 
	 * Шаблон альфа-числовых строк, без специальных символов.
	 */
	public const REGEX_ALPHANUMERIC = '/^[a-zA-Z0-9]+$/';

	/**
	 * Pattern for URLs, validating common schemes and domain structures.
	 * 
	 * Шаблон для URL, валидация общепринятых схем и структур доменов.
	 */
	public const REGEX_URL = '/\b(?:https?|ftp):\/\/[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,}(?:\/\S*)?/';

	/**
	 * Pattern for email addresses, validating common email syntax.
	 * 
	 * Шаблон для адресов электронной почты, валидация общепринятого синтаксиса.
	 */
	public const REGEX_EMAIL = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

	/**
	 * Pattern for international phone numbers, validating various country codes and formats.
	 * 
	 * Шаблон для международных телефонных номеров, валидация различных страновых кодов и форматов.
	 */
	public const REGEX_PHONE_INTERNATIONAL = '/^\+\d{1,3}\s?\(?\d{1,4}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/';

	/**
	 * Pattern for Hex color codes, validating 3 or 6 digit notations.
	 * 
	 * Шаблон для шестнадцатеричных кодов цвета, валидация нотаций из 3 или 6 цифр.
	 */
	public const REGEX_HEX_COLOR = '/^#?([a-f0-9]{6}|[a-f0-9]{3})$/i';

	/**
	 * Pattern for IPv4 addresses, validating four decimal octets.
	 * 
	 * Шаблон для адресов IPv4, валидация четырех десятичных октетов.
	 */
	public const REGEX_IP_V4 = '/^\d{1,3}(\.\d{1,3}){3}$/';

	/**
	 * Pattern for IPv6 addresses, validating hexadecimal quartets.
	 * 
	 * Шаблон для адресов IPv6, валидация четырехгрупп шестнадцатеричных чисел.
	 */
	public const REGEX_IP_V6 = '/^([\da-fA-F]{1,4}:){7}([\da-fA-F]{1,4})$/';

	/**
	 * Pattern for ISO 8601 date format, validating year-month-day structure.
	 * 
	 * Шаблон для формата даты ISO 8601, валидация структуры год-месяц-день.
	 */
	public const REGEX_ISO8601_DATE = '/^\d{4}-\d{2}-\d{2}$/';

	/**
	 * Pattern for slugs, validating alphanumeric characters and hyphens, commonly used in URLs.
	 * 
	 * Шаблон для слагов, валидация альфа-числовых символов и дефисов, часто используемых в URL.
	 */
	public const REGEX_SLUG = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

	/**
	 * Pattern for Cyrillic text, validating Cyrillic letters and spaces.
	 * 
	 * Шаблон для кириллического текста, валидация кириллических букв и пробелов.
	 */
	public const REGEX_CYRILLIC_TEXT = '/^[\p{Cyrillic}\s]+$/u';

	/**
	 * Pattern for IP addresses, supporting both IPv4 and IPv6.
	 * 
	 * Шаблон для IP-адресов, поддерживающий как IPv4, так и IPv6.
	 */
	public const REGEX_IP_ANY_VERSION = '/^(\d{1,3}(\.\d{1,3}){3}|[\da-fA-F]{1,4}(:[\da-fA-F]{1,4}){7})$/';

	/**
	 * Pattern for validating UUIDs of version 1 and version 4.
	 * 
	 * Шаблон для проверки UUID версии 1 и 4.
	 */
	public const REGEX_UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-4][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

	/**
	 * Pattern for checking time in 24-hour format, with optional seconds.
	 * 
	 * Шаблон для проверки времени в 24-часовом формате, с необязательными секундами.
	 */
	public const REGEX_TIME_24HR_FORMAT = '/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/';

	/**
	 * Simple pattern for credit card number validation.
	 * 
	 * Простой шаблон для проверки номера кредитной карты.
	 */
	public const REGEX_CREDIT_CARD = '/^\d{4}-?\d{4}-?\d{4}-?\d{4}$/';

	/**
	 * Pattern for strong password validation: Minimum 8 characters, at least one letter and one number.
	 * 
	 * Шаблон для проверки надежности пароля: минимум 8 символов, хотя бы одна буква и одна цифра.
	 */
	public const REGEX_PASSWORD_STRONG = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/';

	/**
	 * Pattern for monetary values with up to two decimal places.
	 * 
	 * Шаблон для денежных значений, с до двух знаков после запятой.
	 */
	public const REGEX_TWO_DECIMALS = '/^\d+(\.\d{1,2})?$/';

	/**
	 * Basic pattern for ISBN validation covering ISBN-10 and ISBN-13 formats.
	 * 
	 * Базовый шаблон для проверки ISBN, охватывающий форматы ISBN-10 и ISBN-13.
	 */
	public const REGEX_ISBN = '/^(?:ISBN(?:-1[03])?:? )?(?=\d{10}$|\d{13}$|(?=(?:\d+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?(?:[0-9]+[- ]?){2}[0-9X]$/';

	/**
	 * Pattern for U.S. social security number format.
	 * 
	 * Шаблон для формата американского социального страхового номера.
	 */
	public const REGEX_SOCIAL_SECURITY_NUMBER = '/^\d{3}-\d{2}-\d{4}$/';

	/**
	 * Pattern for geographic coordinates (latitude and longitude).
	 * 
	 * Шаблон для географических координат (широта и долгота).
	 */
	public const REGEX_GEO_COORDINATES = '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/';

	/**
	 * Pattern for tags, separated by commas without spaces or with a single space after each comma.
	 * 
	 * Шаблон для тегов, разделенных запятыми без пробелов или с одним пробелом после каждой запятой.
	 */
	public const REGEX_TAGS_COMMA_SEPARATED = '/^(\w+)(,\s*\w+)*$/';
}