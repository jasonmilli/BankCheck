<?php
// The following array is taken from http://www.http://en.wikipedia.org/wiki/Bank_card_number on 11/06/2015
$issuers = array(
    array('issuer' => 'American Express', 'bounds' => array(array('min' => 34), array('min' => 37)), 'length' => array(15), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'Bankcard', 'bounds' => array(array('min' => 5610), array('min' => 560221, 'max' => 560225)), 'length' => array(16), 'validation' => 'Luhn', 'active' => 'no'),
    array('issuer' => 'China UnionPay', 'bounds' => array(array('min' => 62)), 'length' => array(16,17,18,19), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'Diners Club Carte Blanche', 'bounds' => array(array('min' => 300, 'max' => 305)), 'length' => array(14), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'Diners Club enRoute', 'bounds' => array(array('min' => 2014), array('min' => 2149)), 'length' => array(15), 'active' => 'no'),
    array(
        'issuer' => 'Diners Club International',
        'bounds' => array(array('min' => 300, 'max' => 305), array('min' => 309), array('min' => 36), array('min' => 38, 'max' => 39)),
        'length' => array(14),
        'validation' => 'Luhn',
        'active' => 'yes'
    ),
    array('issuer' => 'Diners Club United States & Canada', 'bounds' => array(array('min' => 54), array('min' => 55)), 'length' => array(14), 'validation' => 'Luhn', 'active' => 'yes'),
    array(
        'issuer' => 'Discover Card',
        'bounds' => array(array('min' => 6011), array('min' => 622126, 'max' => 622925), array('min' => 644, 'max' => 649), array('min' => 65)),
        'length' => array(16),
        'validation' => 'Luhn',
        'active' => 'yes'
    ),
    array('issuer' => 'InterPayment', 'bounds' => array(array('min' => 636)), 'length' => array(16,17,18,19), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'InstaPayment', 'bounds' => array(array('min' => 637, 'max' => 639)), 'length' => array(16), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'JCB', 'bounds' => array(array('min' => 3528, 'max' => 3589)), 'length' => array(16), 'validation' => 'Luhn', 'active' => 'yes'),
    array(
        'issuer' => 'Laser',
        'bounds' => array(array('min' => 6304), array('min' => 6706), array('min' => 6771), array('min' => 6709)),
        'length' => array(16,17,18,19),
        'validation' => 'Luhn',
        'active' => 'no'
    ,
    array(
        'issuer' => 'Maestro',
        'bounds' => array(array('min' => 500000, 'max' => 509999), array('min' => 560000, 'max' => 699999)),
        'length' => array(12,13,14,15,16,17,18,19),
        'validation' => 'Luhn',
        'active' => 'yes'
    ),
    array('issuer' => 'Dankort', 'bounds' => array(array('min' => 5019)), 'length' => array(16), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'MasterCard', 'bounds' => array(array('min' => 222100, 'max' => 272099)), 'length' => array(16), 'validation' => 'Luhn', 'active' => 'no'),
    array('issuer' => 'MasterCard', 'bounds' => array(array('min' => 51, 'max' => 55)), 'length' => array(16), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'Solo', 'bounds' => array(array('min' => 6334), array('min' => 6767)), 'length' => array(16,18,19), 'validation' => 'Luhn', 'active' => 'no'),
    array(
        'issuer' => 'Switch',
        'bounds' => array(array('min' => 4905), array('min' => 4905), array('min' => 4911), array('min' => 4936), array('min' => 564182), array('min' => 633110), array('min' => 6333), array('min' => 6759)),
        'length' => array(16,18,19),
        'validation' => 'Luhn',
        'active' => 'no'
    ),
    array('issuer' => 'Visa', 'bounds' => array(array('min' => 4)), 'length' => array(13,16), 'validation' => 'Luhn', 'active' => 'yes'),
    array('issuer' => 'UATP', 'bounds' => array(array('min' => 1)), 'length' => array(15), 'validation' => 'Luhn', 'active' => 'yes')
);
