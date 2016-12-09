# Change Log
All notable changes to this project will be documented in this file.

## [0.1.0] - 2015-03-13
### Changed
- Adheres to standard when extending parent class (match signatures)
- Makes the package installable via Composer
- Replaces HTTP_Request2 with Guzzle

## [1.1.5] - 2016-06-02
### Changed
- Added digitsMatchBLeg parameter to Dial XML

## [1.2.1] - 2016-12-08
### Changed
- [bug] removed $body from Element::addDial, incorrectly used as attributes which is the 2nd parameter  
- [bug] fixed $params not being passed in RestAPI::buy_phone_number
- [bug] fixed $params not being passed in RestAPI::rent_number
- [misc] added PHPDocs
- [misc] project now follows PSR-4 standard