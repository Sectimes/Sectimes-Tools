<h1 align="center">Sectimes Tools Manager</h1>

![Sectimes](https://badgen.net/badge/Sectimes/Cyber/purple?icon=github&scale=1.5)

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)

![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)
[![Licence](https://img.shields.io/github/license/Ileriayo/markdown-badges?style=for-the-badge)](./LICENSE)
---

Welcome to an innovative open-source project designed to streamline the management of security tools, making them more accessible and user-friendly. Our platform - **Sectimes Tools Manager** is dedicated to simplifying the use of essential security tools, written in PHP - Laravel.

![Sectimes-tools-example-pic](/public/img/Sectimes-tools-example-pic.png)
## Introduction:
This 'Webtool' is mainly built for easing your pentesting work by intergrating many tools together (`nmap`, `ffuf`, `gobuster`,...), which could help you not to install lots of your tools on your local computer.

## Requirements:
- PHP 7.4+
- Nginx
- Laravel
- Composer
- Docker (Optional) (If you don't use Docker, you may have to create mysql db yourself and config your own settings)

## Installation:
### Manually Build 
- Clone the project:
```bash
git clone git@github.com:Sectimes/Sectimes-Tools.git
```
- Build the project:
```bash
cd Sectimes-Tools && php artisan serve
```
- By default, the project should be built on port 8000.
- Go to `http://localhost:8000` to connect to the app.

### Build with Docker:
(Note that the PHP version we are using in Docker is 7.4.33)
- First, clone the project:
```bash
git clone git@github.com:Sectimes/Sectimes-Tools.git
```
- Get into the folder and build the project:
```bash
cd Sectimes-Tools && docker-compose up
```
- If things run smoothly, by default, the project would comes up on port 8000.
- Go to `http://localhost:8000` to connect to the app.

## Payload lists:
- XSS Payload list: [https://github.com/payloadbox/xss-payload-list](https://github.com/payloadbox/xss-payload-list)
- Command Injection Payload list: [https://github.com/payloadbox/command-injection-payload-list](https://github.com/payloadbox/command-injection-payload-list)
- Directories fuzzing Payload lists [https://gist.github.com/yassineaboukir/8e12adefbd505ef704674ad6ad48743d](https://gist.github.com/yassineaboukir/8e12adefbd505ef704674ad6ad48743d) And [https://github.com/clarkvoss/AEM-List/tree/main](https://github.com/clarkvoss/AEM-List/tree/main) And [https://github.com/digination/dirbuster-ng/blob/master/wordlists/common.txt](https://github.com/digination/dirbuster-ng/blob/master/wordlists/common.txt)

## Developers:
Many thanks to our core Developers. (They require to hide their names, so we tend to call them ***Anonymous Duck*** and ***Anonymous Monkey***).

Template UI Developer: [Creative Tim](https://www.creative-tim.com).
- Original Template Project: https://www.creative-tim.com/product/black-dashboard

## License:
Copyright © 2023 Sectimes Cyber [sectimescyber@gmail.com](sectimescyber@gmail.com).

This work is free. You can redistribute it and/or modify it under the terms of the MIT License. See the [COPYING.md](https://github.com/wallabag/wallabag/blob/master/COPYING.md) file for more details.

## Bugs / Security bugs:
> [!IMPORTANT]
> If you discover bugs, please report them in [Github Issues](https://github.com/Sectimes/Sectimes-Tools/issues) or on [huntr.dev](https://huntr.dev).

## Contact:
Contact us via this email: [sectimescyber@gmail.com](sectimescyber@gmail.com).