#!/bin/bash

cp /app/crontab /etc/cron.d/crontab
chmod 0744 /etc/cron.d/crontab
crontab /etc/cron.d/crontab
touch /var/log/cron.log
cron

tail -f /var/log/cron.log