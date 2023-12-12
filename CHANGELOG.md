# Release Notes for v0.x.x.pre-alpha

## [Unreleased](https://github.com/The-FireHub-Project/FireHub/compare/v0.1.5-alpha.1...develop-pre-alpha-m2)

## [v0.1.6](https://github.com/The-FireHub-Project/FireHub/compare/v0.1.5-alpha.1...v0.1.6-alpha.1) - (2023-11-08)

### Added
- Created Register support class ([#9](https://github.com/The-FireHub-Project/FireHub/issues/9), [6adc995](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/6adc995))

## [v0.1.5](https://github.com/The-FireHub-Project/FireHub/compare/v0.1.4-alpha.1...v0.1.5-alpha.1) - (2023-11-08)

### Added
- Added Cls and ObjCls to FireHub main class preloader list ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [8fcd3fb](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/8fcd3fb))
- Created _autoload functionality for Autoload ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [8ccaddf](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/8ccaddf))
- Updated methods on low-level Str classes ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [b37c67d](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/b37c67d))
- Created Countable interface ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [f4b3874](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/f4b3874))
- Created Iterables interface ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [e6cd70f](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/e6cd70f))
- Created Regex low-level classes ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [e82e635](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/e82e635))
- Created Char high-level class ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [5c99cc0](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/5c99cc0))
- Added regexMatch method in Char high-level class ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [fcb28ff](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/fcb28ff))
- Added replaceFunc method in Regex low-level classes ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [90eb164](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/90eb164))
- Added reverse method in StrSB low-level class ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [df8821a](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/df8821a))
- Added various new methods to Arr low-level class ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [7327d8b](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/7327d8b))
- Changed return for base traits to never ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [8663958](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/8663958))
- Created Iterables contracts ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [8d8ac87](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/8d8ac87))
- Created ArrayAccessible contract ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [796e092](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/796e092))
- Created Countable contract ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [c29e7f6](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/c29e7f6))
- Created array helper and add it to the main class ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [294e93b](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/294e93b))
- Created data helper ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [2f63822](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/2f63822))
- Created Comparison enum ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [7e59b83](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/7e59b83))
- Created Collections ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [65b0da7](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/65b0da7))
- Created Str ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [f06084d](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/f06084d))
- Created Zwick high-level classes ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [a69f60a](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/a69f60a))
- Created number helper ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [0598a48](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/0598a48))
- Created LogBase enum ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [d71a21c](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/d71a21c))
- Created number helper ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [1ade27a](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/1ade27a))
- Created Number high-level class ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [c550fd5](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/c550fd5))

### Fixed
- Fixed missing encoding on split method ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [8c618f8](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/8c618f8))
- Fixed file description for constant files ([8c618f8](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/8c618f8))
- Fixed error on '0' string in StrSB low-level class ([#8](https://github.com/The-FireHub-Project/FireHub/issues/8), [ec09180](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/ec09180))

## [v0.1.4](https://github.com/The-FireHub-Project/FireHub/compare/v0.1.3-alpha.1...v0.1.4-alpha.1) - (2023-10-13)

### Added
- Created Path support class ([#7](https://github.com/The-FireHub-Project/FireHub/issues/7), [0486894](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/0486894))
- Created Autoload class ([#7](https://github.com/The-FireHub-Project/FireHub/issues/7), [758328b](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/758328b))
- Added autoload to the main FireHub class ([#7](https://github.com/The-FireHub-Project/FireHub/issues/7), [0ff12d2](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/0ff12d2))

## [v0.1.3](https://github.com/The-FireHub-Project/FireHub/compare/v0.1.2-alpha.1...v0.1.3-alpha.1) - (2023-10-12)

### Added
- Created String, Encoding and Data low-level classes ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [d43be6d](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/d43be6d))
- Created Round number enum ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [6110816](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/6110816))
- Created Num low-level classes ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [a2b3f17](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/a2b3f17))
- Created Order and Sort enums ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [bb5d535](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/bb5d535))
- Created Arr low-level class ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [c8110dd](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/c8110dd))
- Created Class and object low level classes ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [cb8662c](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/cb8662c))
- Created Iterator low-level classes ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [0353028](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/0353028))
- Created SPLAutoload low level classes ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [c95fc46](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/c95fc46))
- Created date and time low-level classes ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [7c46025](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/7c46025))
- Created files low level classes ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [9a8b563](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/9a8b563))
- Created support constants ([#5](https://github.com/The-FireHub-Project/FireHub/issues/5), [45ea60f](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/45ea60f))
- Created support contracts ([#6](https://github.com/The-FireHub-Project/FireHub/issues/6), [02008a7](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/02008a7))
- Created base classes ([#6](https://github.com/The-FireHub-Project/FireHub/issues/6), [e3b3d95](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/e3b3d95))
- Added base traits to all classes ([#6](https://github.com/The-FireHub-Project/FireHub/issues/6), [c1db243](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/c1db243))
- Created Kernel ([#6](https://github.com/The-FireHub-Project/FireHub/issues/6), [d8f89fb](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/d8f89fb))

## [v0.1.2](https://github.com/The-FireHub-Project/FireHub/compare/v0.1.1-alpha.1...v0.1.2-alpha.1) - (2023-09-21)

### Added
- Created UML diagram theme ([#4](https://github.com/The-FireHub-Project/FireHub/issues/4), [b24d899](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/b24d899))

## [v0.1.1](https://github.com/The-FireHub-Project/FireHub/compare/v0.1.0-alpha.1...v0.1.1-alpha.1) - (2023-09-21)

### Added
- Created landing init files for phar archive ([#2](https://github.com/The-FireHub-Project/FireHub/issues/2), [2bd866b](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/2bd866b))
- Created PHAR archive with token ([#2](https://github.com/The-FireHub-Project/FireHub/issues/2), [fa55cf1](https://github.com/The-FireHub-Project/FireHub/pull/3/commits/fa55cf1))