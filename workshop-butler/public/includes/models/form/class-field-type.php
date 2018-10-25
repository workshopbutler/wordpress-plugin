<?php
/**
 * The file that defines the Field_Type class
 *
 * @link       https://workshopbutler.com
 * @since      2.0.0
 *
 * @package    WorkshopButler
 */

namespace WorkshopButler;

/**
 * Type of the field
 *
 * @package WorkshopButler
 */
abstract class Field_Type {
	const CHECKBOX  = 'checkbox';
	const TEXT_AREA = 'textarea';
	const SELECT    = 'select';
	const COUNTRY   = 'country';
	const TICKET    = 'ticket';
	const TEXT      = 'text';
	const EMAIL     = 'email';
	const DATE      = 'date';
}
