# Lemp-webapp
a generic example webapp implementing online payment methods from suriname:
currently implemented methods
 - [Nummus](nummus.world)

it is based on the [clemp docker image](https://github.com/fuseteam/clemp)

containers can be run with
```
docker run -d -v /absolute/path/to/www:/var/www/html fuseteam/lemp-webapp
```

with docker compose file the project brought up with one command:
```
docker-compose up -d
```
it will populate the www folder with the document root and the www-data with the database, both configurable in docker-compose.yml. both www and www-data must be created manually before running the above command with the default docker-compose file

## todo
- contact form
- edit table entries
- delete table entries
- user log in
- user permission system
- column aliases
- specifying required fields
- multi-lingual support

## structure
this project makes use of libraries that are present in the clemp docker image mentioned above
the full document root can fetched by modifying the following line in docker-compose.yml
```diff
    testcon:
      image: 'fuseteam/lemp-webapp'
      ports:
        - '8090:80'
      volumes:
++        - 'documentroot:/var/www'
--        - 'documentroot:/var/www/html'
      depends_on:
        - mariadb
```
### www
this contains the actual webapp build with php css html and javascript
of which
- model: the way components of the webapp are modeled in the database
- view: the layout and style of the components the user will interact with
- controller: the server side logic that takes the appropiate model and creates or sends it to the appropiate view
### etc
this contains the configuration of nginx and the rest of the server stack
### run.sh
this contains the script the container runs to initialize the webapp enviroment
### Dockerfile
this file contains the configuration used by docker to create the docker image which the webapp can run on without issue
### docker-compose
this file contains the configuration used by docker-compose to setup the webapp application with the `docker-compose up -d` command
