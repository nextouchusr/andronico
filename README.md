## Installation
Following these steps to install the project correctly:
- Add your user to docker group and then reboot your system: `sudo usermod -a -G docker <user>`
- Copy and rename `.env.dist` into `.env` and set variables
- Copy and rename `docker/docker-compose.override.yml.dist` into `docker/docker-compose.override.yml` and set ports
- Copy and rename `auth.json.sample` into `auth.json` and set Magento authorization credentials
- Run `make run` to download Docker images and start containers
- Run `make php_container` to connect into php container
- Run `make magento_install` into PHP container to install Magento
- Visit http://172.50.0.10 for the storefront area
- Visit http://172.50.0.10/admin_o35t7f for the back-office area

## PHPStorm
To configure your IDE follow these steps:
* Go to File -> Manage IDE Settings -> Import Settings and import settings located at: `/dev/tools/phpstorm/settings.zip`
* Configure static analysis and quality tools by following this guide: https://devdocs.magento.com/guides/v2.4/test/static/static-analysis.html
* Run `bin/magento dev:urn-catalog:generate .idea/misc.xml` on `php_container` to generate *.xsd mappings for the IDE to highlight xml

## Troubleshooting
### Elasticsearch container doesn't start
Increase virtual memory by running: `sudo sysctl -w vm.max_map_count=262144`
### The assets don't load correctly
- Go to database and run: `INSERT INTO core_config_data (path, value) VALUES ('dev/static/sign', 0) ON DUPLICATE KEY UPDATE value=0`
- Then build frontend: `make build_frontend`
- Visit your project url to check if all work properly

## Infrastructure and Technology
Magento 2.4.2 needs the following technologies to work:
- Web Server: Apache 2.4 or nginx 1.x
- PHP: 7.4.0
- PHP Extensions:
  * ext-bcmath
  * ext-ctype
  * ext-curl
  * ext-dom
  * ext-gd
  * ext-hash
  * ext-iconv
  * ext-intl
  * ext-mbstring
  * ext-opcache
  * ext-openssl
  * ext-pdo_mysql
  * ext-simplexml
  * ext-soap
  * ext-xsl
  * ext-zip
  * ext-sockets
  * ext-apcu
- Apache Modules:
  * mod_deflate
  * mod_expires
  * mod_headers
  * mod_rewrite
  * mod_security
  * mod_ssl
  * mod_version
- System Libraries:
  * Libsodium
- Package manager: Composer 2.x
- Front-end: Node 14.16.1 with npm 6.14.12
- Database: MySQL 8.0 or MariaDB 10.4
- Caching: Redis 6.0
- Session storage: Redis 6.0
- Search engine: Elasticsearch 7.x
- Recommend: Docker

### Docker containers
The application runs using Docker containers. Starting from Docker images, the following containers have been configured:
* php: container with PHP 7.4 and Apache webserver where to run Magento framework
* db: container with MariaDB 10.3.28 where is hosted Magento database
* cache: container with Redis 6.2.5 used for users session storage and page caching
* cron: container used to run scheduled operations
* sftp: container used externally for file transfer from ERP and e-commerce
* ftpd: container used internally to access files transferred from the ERP 

Containers belong to the same `nextouch_net` network and, except for the `php` container which has a configuration specific for the Magento framework,
the others are based on simple Docker images linked and configured through a `.env` file.

#### PHP container
Starting from the php: 7.4-apache image, the Debian libraries necessary for Magento startup (libsodium, mcrypt, zip etc.) are installed,
as well as a whole series of PHP extensions without which the installation would not be successful (opcache, mbstring, soap, sockets, pdo_mysql etc.).
To improve the speed and debugging of the application, the APCU extension and Xdebug have also been installed.
The container also installs the latest LTS version of Node 14.x, necessary for installing dependencies via npm (Grunt, Husky, Conventional Commit ...).
Finally, create a magento user and group and set permissions on the working directory.
