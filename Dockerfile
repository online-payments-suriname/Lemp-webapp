############################################################
# Dockerfile to build Centos-LEMP installed  Container
# Based on CentOS
############################################################

# Set the base image to preconfigured lemp
FROM fuseteam/clemp

# File Author / Maintainer
MAINTAINER Fuseteam <fusekai@outlook.com>

# Adding the default file
ADD www /var/www/html

ADD run.sh /run.sh
RUN chmod 755 /*.sh

## Executing supervisord
CMD ["/run.sh"]
