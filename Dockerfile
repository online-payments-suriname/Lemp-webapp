############################################################
# Dockerfile to build Centos-LEMP installed  Container
# Based on CentOS
############################################################

# Set the base image to preconfigured lemp
FROM fuseteam/lemp

# File Author / Maintainer
MAINTAINER Fuseteam <fusekai@outlook.com>

# Enviroment variable for setting Password of MySQL
ENV MYSQL_ROOT_PASS root

# Adding the default file
ADD www /var/www

# add a custom nginx config from the base
ADD etc/default.conf /etc/nginx/conf.d/default.conf

# Adding scripts
ADD run.sh /run.sh
RUN chmod 755 /*.sh

## Executing supervisord
CMD ["/run.sh"]
