# Lemp-webapp
a generic example webapp build using php bootstrap and javascript on top of the lemp webstack
the image is based on the [clemp image](https://github.com/fuseteam/docker-centos-lemp)

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
contact form
edit table entries
delete table entries
user log in
user permission system
column aliases
adding columns to existing tables
specifying required fields
multi-lingual support
