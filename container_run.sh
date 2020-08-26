#!/bin/bash
function print_usage() {
  echo "Usage: conrun runs a docker container using sane default mappings

  Required flags:
	-h specifies which host ports to map the ports of the container
	-v/V specifies the directory to mount as a dockervolume
		v for mounting both a webroot and a database folder
		V for mounting only a database folder
			it always appends '-data' to the database foldername specified
	-i specifies which docker image to use

  Optional flags:
	-n specifies the container name
	-p prompts for the database password

  Example conrun -h 90 -v www -n test -i fuseteam/lemp will
	  map host ports 8090 and 3390 to the container's ports 80 and 3306 respectivily
	  mount the directory www into the container's directory /var/www
	  mount www-data into the container's directory /var/lib/mysql
	  name the container 'test'
	  use the docker image fuseteam/lemp
	  and does not prompt for the database password
	  by executing the command docker run -d -p 8090:80 - p 3390:3306 -v [currentpath]/www-data:/var/lib/mysql -v [currentpath]/www:/var/www --name test fuseteam/lemp
	  where [currentpath] is the current working directory"
}

while getopts 'h:d:v:V:n:pi:' flag; do
	case "${flag}" in
		h) portflag=true;httpport="-p80${OPTARG}:80";dbport="-p33${OPTARG}:3306";;
		v) volflag=true;voldir=${PWD}/${OPTARG%/};nginxvol="-v$voldir:/var/www/html";datavol="-v$voldir-data:/var/lib/mysql-data";;
		V) nonginxvol=true;volflag=true;voldir=${PWD}/${OPTARG%/};datavol="-v$voldir-data:/var/lib/mysql-data";;
		n) conname="--name ${OPTARG}";;
		p) read -s -p "password:" password;pass="-ePASSWORD=$password ";;
		i) image="${OPTARG}";;
	esac
done
[[ -n $nonginxvol ]] && vol=$datavol ||	vol=$datavol" "$nginxvol;
[[ -n $portflag && -n $volflag && -n $image ]] && docker run -d $httpport $dbport $vol $pass$conname $image || print_usage
