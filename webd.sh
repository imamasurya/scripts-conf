#!/bin/sh
#
# webd.sh
#
# start/stop/restart httpd, php-fpm & mysqld 
# in Slackware 14.1
#
# Author : Imam A. Surya <imamas64@gmail.com>

# start
web_start() {
    echo "Starting webserver..."
    sh /etc/rc.d/rc.httpd start
    sh /etc/rc.d/rc.php-fpm start
    sh /etc/rc.d/rc.mysqld start
}

# stop
web_stop() {
    echo "Stopping webserver..."
    sh /etc/rc.d/rc.httpd stop
    sh /etc/rc.d/rc.php-fpm stop
    sh /etc/rc.d/rc.mysqld stop
}

# restart
web_restart() {
    echo "Restarting webserver..."
    #sh /etc/rc.d/rc.httpd restart
    #sh /etc/rc.d/rc.php-fpm restart
    #sh /etc/rc.d/rc.mysqld restart
    web_stop
    web_start
}

case "$1" in
    'start')
        web_start
        ;;
    'stop')
        web_stop
        ;;
    'restart')
        web_restart
        ;;
    *)
        echo "Usage $0 start|stop|restart"
esac


