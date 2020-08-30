[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

<!-- PROJECT INFO -->
<br />
<p align="center">

  <h3 align="center">AlhasanIQ/ZainCashSDK</h3>

  <p align="center">
    AN Unofficial PHP SDK for ZainCash Iraq
    <br />
    <a href="https://github.com/alhasaniq/zaincash-sdk/tree/master/examples">Examples</a>
    ·
    <a href="https://github.com/alhasaniq/zaincash-sdk/issues">Report a Bug</a>
    ·
    <a href="https://github.com/alhasaniq/zaincash-sdk/issues">Request Feature</a>
  </p>
</p>

<!-- TABLE OF CONTENTS -->

## Table of Contents

-   [About the Project](#about-the-project)
    -   [Motive](#motive)
-   [Important Note](#important-note)
-   [Examples](#examples)
-   [Contributing](#contributing)
-   [License](#license)
-   [Contact](#contact)

<!-- ABOUT THE PROJECT -->

## About The Project

This is an Unofficial PHP (Composer & PSR Compatible) SDK to integrate [ZainCash Iraq](https://zaincash.iq) with php applications.

### Motive:

-   The Official SDK is un-documented.
-   The Official SDK is not Object Oriented (imperative).
-   The Official SDK has some bad practices and 0 encapsulation of logic.

<!-- GETTING STARTED -->

## Important Note

Most of the code here is not properly tested and hasn't been touched since i worked at ZainCash in 2015-2016.

Kindly, inspect the code closely and make sure it fits your needs.

The code is provided as-is and no guarantees are given regarding functionality/security.

While this project started when i used to work at ZainCash (2015-2016), I wrote all of this code on my own spare time.  

## Installation

While the package is not suited for production usage at its current stage, and is mainly intended for educational purposes (for now), below is how you can install it to an existing composer project:

Modify your `composer.json` to add a repositories source and a require statement:

```json
{
    ...

	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/alhasaniq/zaincash-sdk.git"
		}
	],

	"require": {
		"alhasaniq/zaincash-sdk": "master"
	}
}
```

<!-- EXAMPLES -->

## Usage

For Usage Examples, kindly refer to the [`examples` folder](https://github.com/alhasaniq/zaincash-sdk/tree/master/examples) which includes 2 examples of the 2 main endpoints needed:

-   [An endpoint](https://github.com/alhasaniq/zaincash-sdk/blob/master/examples/example-init.php) to initiate the transaction (charge the user), which redirects users to zaincash to finish the payment.
-   [Another endpoint](https://github.com/alhasaniq/zaincash-sdk/blob/master/examples/example-redirect.php) to capture the transaction status and info, after being redirected back to your application (from zaincash).

<!-- CONTRIBUTING -->

## Contributing

The project is at a _hobbyist-project_ state, where it is not properly tested/updated.

If you use this on production, your feedback is highly appreciated.

<!-- LICENSE -->

## License

Distributed under the MIT License. See `LICENSE` for more information.

<!-- CONTACT -->

## Contact

Alhasan A. AL-Nasiry - [@alhasaniq](https://twitter.com/alhasaniq)

Project Link: [https://github.com/alhasaniq/zaincash-sdk](https://github.com/alhasaniq/zaincash-sdk)

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[contributors-shield]: https://img.shields.io/github/contributors/alhasaniq/zaincash-sdk.svg?style=flat-square
[contributors-url]: https://github.com/alhasaniq/zaincash-sdk/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/alhasaniq/zaincash-sdk.svg?style=flat-square
[forks-url]: https://github.com/alhasaniq/zaincash-sdk/network/members
[stars-shield]: https://img.shields.io/github/stars/alhasaniq/zaincash-sdk.svg?style=flat-square
[stars-url]: https://github.com/alhasaniq/zaincash-sdk/stargazers
[issues-shield]: https://img.shields.io/github/issues/alhasaniq/zaincash-sdk.svg?style=flat-square
[issues-url]: https://github.com/alhasaniq/zaincash-sdk/issues
[license-shield]: https://img.shields.io/github/license/alhasaniq/zaincash-sdk.svg?style=flat-square
[license-url]: https://github.com/alhasaniq/zaincash-sdk/blob/master/LICENSE
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=flat-square&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/alhasaniq
