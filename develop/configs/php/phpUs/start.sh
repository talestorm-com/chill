#!/bin/bash

chown root:root /etc/cron.d/cron
chmod 644 /etc/cron.d/cron
service cron start

/usr/bin/apache2-foreground
