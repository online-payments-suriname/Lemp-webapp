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
