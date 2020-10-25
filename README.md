# Lemp-webapp
a generic example webapp build using php bootstrap and javascript on top of the lemp webstack
the image is based on the [clemp image](https://github.com/fuseteam/clemp)

a prebuild version of the image can be pulled with
```
docker pull fuseteam/lemp-webapp
```
containers using this images as a base can mount files into /var/www/html using the following command
```
docker run -d -v /absolute/path/to/directory:/var/www/html fuseteam/lemp-webapp
```
note that when mounting it over writes the content of the folder as such it requires at least an index file

with docker compose file the prebuilt image can used and brought up using one command:
```
docker-compose up -d
```
the way it is set up with with populate the www folder with the document root and the www-data with the database, both configurable in docker-compose.yml. both www and www-data must be created manually before running the above command with the default docker-compose file

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
### www
this contains the actual webapp build with php css html and javascript
of which
- model: the way components of the webapp are modeled in the database
- view: the layout and style of the components the user will interact with
- controller: the server side logic that takes the appropiate model and creates or sends it to the appropiate view
- bootstrap: the bootstrap library for easy styling
- pdfjs: the pdf viewer application by mozilla that be embedded into views
- fpdf: php library for generating pdf files
- fontawesome: the fontawesome library to create text icons for easy use
### etc
this contains the configuration of nginx and the rest of the server stack
### run.sh
this contains the script the container runs to initialize the webapp enviroment
### Dockerfile
this file contains the configuration used by docker to create the docker image which the webapp can run on without issue
### docker-compose
this file contains the configuration used by docker-compose to setup the webapp application with the `docker-compose up -d` command
