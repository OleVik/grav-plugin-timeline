<?php

namespace Bordeux\SchemaOrg\Thing\CreativeWork;

/**
 * Generated by bordeux/schema
 *
 * @author Krzysztof Bednarczyk <schema@bordeux.net>
 * @link http://schema.org/Book
 *
 *
 * -------------------------------- BookEdition ---------------------------------------------
 *
 * @property string|string[] bookEdition
 *
 * @method string|string[] getBookEdition() The edition of the book.
 *
 * @method Book setBookEdition(string $bookEdition ) setBookEdition(string[] $bookEdition )The edition of the book.
 *
 *
 * -------------------------------- BookFormat ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\Intangible\Enumeration\BookFormatType|\Bordeux\SchemaOrg\Thing\Intangible\Enumeration\BookFormatType[] bookFormat
 *
 * @method \Bordeux\SchemaOrg\Thing\Intangible\Enumeration\BookFormatType|\Bordeux\SchemaOrg\Thing\Intangible\Enumeration\BookFormatType[] getBookFormat() The format of the book.
 *
 * @method Book setBookFormat(\Bordeux\SchemaOrg\Thing\Intangible\Enumeration\BookFormatType $bookFormat ) setBookFormat(\Bordeux\SchemaOrg\Thing\Intangible\Enumeration\BookFormatType[] $bookFormat )The format of the book.
 *
 *
 * -------------------------------- Illustrator ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\Person|\Bordeux\SchemaOrg\Thing\Person[] illustrator
 *
 * @method \Bordeux\SchemaOrg\Thing\Person|\Bordeux\SchemaOrg\Thing\Person[] getIllustrator() The illustrator of the book.
 *
 * @method Book setIllustrator(\Bordeux\SchemaOrg\Thing\Person $illustrator ) setIllustrator(\Bordeux\SchemaOrg\Thing\Person[] $illustrator )The illustrator of the book.
 *
 *
 * -------------------------------- Isbn ---------------------------------------------
 *
 * @property string|string[] isbn
 *
 * @method string|string[] getIsbn() The ISBN of the book.
 *
 * @method Book setIsbn(string $isbn ) setIsbn(string[] $isbn )The ISBN of the book.
 *
 *
 * -------------------------------- NumberOfPages ---------------------------------------------
 *
 * @property Integer|Integer[] numberOfPages
 *
 * @method Integer|Integer[] getNumberOfPages() The number of pages in the book.
 *
 * @method Book setNumberOfPages(Integer $numberOfPages ) setNumberOfPages(Integer[] $numberOfPages )The number of pages in the book.
 *
 *
 */
 class Book extends \Bordeux\SchemaOrg\Thing\CreativeWork {

 }