<?php

namespace Bordeux\SchemaOrg\Thing\Intangible\StructuredValue;

/**
 * Generated by bordeux/schema
 *
 * @author Krzysztof Bednarczyk <schema@bordeux.net>
 * @link http://schema.org/OwnershipInfo
 *
 *
 * -------------------------------- AcquiredFrom ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\Organization|\Bordeux\SchemaOrg\Thing\Organization[]|\Bordeux\SchemaOrg\Thing\Person|\Bordeux\SchemaOrg\Thing\Person[] acquiredFrom
 *
 * @method \Bordeux\SchemaOrg\Thing\Organization|\Bordeux\SchemaOrg\Thing\Organization[]|\Bordeux\SchemaOrg\Thing\Person|\Bordeux\SchemaOrg\Thing\Person[] getAcquiredFrom() The organization or person from which the product was acquired.
 *
 * @method OwnershipInfo setAcquiredFrom(\Bordeux\SchemaOrg\Thing\Organization $acquiredFrom ) setAcquiredFrom(\Bordeux\SchemaOrg\Thing\Organization[] $acquiredFrom ) setAcquiredFrom(\Bordeux\SchemaOrg\Thing\Person $acquiredFrom ) setAcquiredFrom(\Bordeux\SchemaOrg\Thing\Person[] $acquiredFrom )The organization or person from which the product was acquired.
 *
 *
 * -------------------------------- OwnedFrom ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\SchemaDateTime|\Bordeux\SchemaOrg\SchemaDateTime[] ownedFrom
 *
 * @method \Bordeux\SchemaOrg\SchemaDateTime|\Bordeux\SchemaOrg\SchemaDateTime[] getOwnedFrom() The date and time of obtaining the product.
 *
 * @method OwnershipInfo setOwnedFrom(\Bordeux\SchemaOrg\SchemaDateTime $ownedFrom ) setOwnedFrom(\Bordeux\SchemaOrg\SchemaDateTime[] $ownedFrom )The date and time of obtaining the product.
 *
 *
 * -------------------------------- OwnedThrough ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\SchemaDateTime|\Bordeux\SchemaOrg\SchemaDateTime[] ownedThrough
 *
 * @method \Bordeux\SchemaOrg\SchemaDateTime|\Bordeux\SchemaOrg\SchemaDateTime[] getOwnedThrough() The date and time of giving up ownership on the product.
 *
 * @method OwnershipInfo setOwnedThrough(\Bordeux\SchemaOrg\SchemaDateTime $ownedThrough ) setOwnedThrough(\Bordeux\SchemaOrg\SchemaDateTime[] $ownedThrough )The date and time of giving up ownership on the product.
 *
 *
 * -------------------------------- TypeOfGood ---------------------------------------------
 *
 * @property \Bordeux\SchemaOrg\Thing\Product|\Bordeux\SchemaOrg\Thing\Product[] typeOfGood
 *
 * @method \Bordeux\SchemaOrg\Thing\Product|\Bordeux\SchemaOrg\Thing\Product[] getTypeOfGood() The product that this structured value is referring to.
 *
 * @method OwnershipInfo setTypeOfGood(\Bordeux\SchemaOrg\Thing\Product $typeOfGood ) setTypeOfGood(\Bordeux\SchemaOrg\Thing\Product[] $typeOfGood )The product that this structured value is referring to.
 *
 *
 */
 class OwnershipInfo extends \Bordeux\SchemaOrg\Thing\Intangible\StructuredValue {

 }