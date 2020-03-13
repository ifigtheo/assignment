# NET2GRID-assignment
The "NET2GRID-Assignment" is a Symfony application, created to consume data from an API, send the results to an exchange on a RabbitMQ instance where they are filtered, consume the filtered results from a queue and store these in a database.

### Requirements
```
PHP 7.4.3
MySQL 
Symfony 4.4.5
```

## Installation

[Download Symfony][4] to install the `symfony` binary on your computer 
Install Composer on your computer
Run the following commands:

```bash
$ symfony new my_project --version=4.4
```
```bash
$ composer require symfony/web-server-bundle --dev
```
```bash
$ composer require symfony/orm-pack
```
```bash
$ composer require symfony/maker-bundle
```
```bash
$ composer require symfony/http-client
```
```bash
composer require php-amqplib/rabbitmq-bundle
```
## Usage
To run the application the .env file located in the root directory needs to be configured.
User should set values to the following parameters:
```
DATABASE_URL=mysql://<user>:<password>@<host>:<port>/<database_name>
API_HOSTNAME=<hostname>
MESSAGE_QUEUE_HOSTNAME=<RabbitMQ_hostname>
MESSAGE_QUEUE_USERNAME=<username>
MESSAGE_QUEUE_PASSWORD=<password>
MESSAGE_QUEUE_EXCHANGE=<exchange>
MESSAGE_QUEUE=<queue>
MESSAGE_QUEUE_PORT=<port(default 5672)>
```
To run the application:
```
$ cd my_project/
$ php bin/console server:start
```
Access the application in your browser at the given URL https://localhost:8000

When user access the url `https://localhost:8000/data/consumer` then the DataConsumerController.php::index() is activated. To simulate the process of receiving data from the Gateway, the  application consumes data from the API every 15sec 5 times and each time sends them to RabbitMQ. When this process is completed then the application receives the filtered messages from the queue and store them in the database.
The application consist of the following controllers:
- `DataConsumerController` is responsible to support the whole process of data receiving and manipulation
- `ApiController` is responsible to communicate with the API and deliver the data to the application in the correct format
- `MessageController` is responsible to handle the connection with RabbitMQ
- `DataController` is responsible to store and fetch the data from the database.

If database schema does not exists, can be created automatically using the command:
```
 php bin/console doctrine:migrations:migrate
```

## Database Definition
Created a table named `data` to store the following information:
- gatewayId: NOT NULL
- profileId: NOT NULL
- endpointId: NOT NULL
- clusterId: NOT NULL
- attributeId
- value: NOT NULL
- timestamp: NOT NULL
- id: PRIMARY KEY
 
Created the Route `/create/{value}/{timestamp}/{routingKey}` to store the values in the database.
User can see the available Routes using command 
```
php bin/console debug:router
```
## Tools
I used the following tools:
* [PhpStorm](https://www.jetbrains.com/phpstorm/) - IDE
* [XAMMP](https://www.apachefriends.org/index.html) - PHP Development environment
* [RabbitMQ](https://www.rabbitmq.com/) - Open source message broker
* [DBeaver](https://dbeaver.io/) - Universal Database Tool

[4]: https://symfony.com/download
