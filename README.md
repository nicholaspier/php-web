# PHP-WEB
This image is for educational purposes and includes a front-end webserver which references an external database. This webpage will display a site and provide debuggin information along the way. 
1. Display PHP Info. This verifies that the webserver is up, and running. 
2. Connect to database. 
3. Write contents of the `staff_list` table. 

The database is part of a separate image detailed below. 

### Example run. 

```sh
MYSQL_HOST=<IP or Hostname>
MYSQL_USER=sakila
MYSQL_PASSWORD=sakila
MYSQL_DATABASE=sakila

docker run -dit -p 8080:80 \
  -e MYSQL_HOST"${MYSQL_HOST}" \
  -e MYSQL_USER="${MYSQL_USER}" \
  -e MYSQL_PASSWORD="${MYSQL_PASSWORD}" \
  -e MYSQL_DATABASE="${MYSQL_DATABASE}" \
  --name php-web \
  pier/php-web
```

### Variables
* ```DB_SRV``` - The IP or hostname of the dabase server. 
* ```MYSQL_USER``` - Database user credential 
* ```MYSQL_PASSWORD``` - Database user Password
* ```MYSQL_DATABASE``` - Database name with schema for application. 

	
# Database
This isn't a custom image. Instead, we leverage the public mariadb image and pass it variables for initial configuration. 

You can run the database with the following example run command:
```sh
MYSQL_USER=sakila
MYSQL_PASSWORD=sakila
MYSQL_DATABASE=sakila

docker run -dit --name php-db \
  -p 3306:3306 \
  -v ${PWD}/db-data/:/var/lib/mysql \
  -v ${PWD}/schema:/docker-entrypoint-initdb.d  \
  -e MYSQL_RANDOM_ROOT_PASSWORD=yes \
  -e MYSQL_DATABASE=${MYSQL_DATABASE} \
  -e MYSQL_USER=${MYSQL_USER} \
  -e MYSQL_PASSWORD=${MYSQL_PASSWORD} \
  -d mariadb:latest
```
### Volumes
#### Data
`/var/lib/mysql` is the location of the MySQL database. If this exists at time of initialization, the environment variables will **NOT** override it. An existing database takes precedence. 

#### Schema
Initialization
When a container is started for the first time, a new database with the specified name will be created and initialized with the provided configuration variables. Furthermore, it will execute files with extensions .sh, .sql and .sql.gz that are found in `/docker-entrypoint-initdb.d`. Files will be executed in alphabetical order. You can easily populate your mariadb services by mounting a SQL dump into that directory and provide custom images with contributed data. SQL files will be imported by default to the database specified by the MYSQL_DATABASE variable.

### Environment Variables 
* ```MYSQL_ROOT_PASSWORD``` - This variable is mandatory and specifies the password that will be set for the MariaDB root superuser account. In the above example, it was set to P@ssw0rd.
* ```MYSQL_DATABASE``` - This variable is optional and allows you to specify the name of a database to be created on image startup. If a user/password was supplied (see below) then that user will be granted superuser access (corresponding to GRANT ALL) to this database.
* ```MYSQL_USER, MYSQL_PASSWORD``` - These variables are optional, used in conjunction to create a new user and to set that user's password. This user will be granted superuser permissions (see above) for the database specified by the MYSQL_DATABASE variable. Both variables are required for a user to be created. Do note that there is no need to use this mechanism to create the root superuser, that user gets created by default with the password specified by the MYSQL_ROOT_PASSWORD variable.
* ```MYSQL_ALLOW_EMPTY_PASSWORD``` - This is an optional variable. Set to yes to allow the container to be started with a blank password for the root user. NOTE: Setting this variable to yes is not recommended unless you really know what you are doing, since this will leave your MariaDB instance completely unprotected, allowing anyone to gain complete superuser access.
* ```MYSQL_RANDOM_ROOT_PASSWORD``` - This is an optional variable. Set to yes to generate a random initial password for the root user (using pwgen). The generated root password will be printed to stdout (GENERATED ROOT PASSWORD: .....).

### Further Reading
https://hub.docker.com/_/mariadb 


# Kubernetes

*Note* The database pod uses an unmodified mariadb base image. Upon deployment, all configuration, including the schema is passed to the database. 

First, modify the configMap to meet your needs. Database names and logins are customizable. 

Apply the manifests in the k8s directory:
```sh
kubectl apply -f ./k8s
```
