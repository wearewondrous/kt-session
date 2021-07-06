#!/usr/bin/env bash

# Read rights for the logs
chmod o+r /var/log/mysql.err
chmod o+r /var/log/syslog

# copy the config
cp /var/www/drupalvm/config/pimpmylog/config.user.json /usr/share/php/pimpmylog/config.user.json