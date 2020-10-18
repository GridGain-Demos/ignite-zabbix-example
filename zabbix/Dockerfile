FROM zabbix/zabbix-appliance:ubuntu-trunk
LABEL maintainer="akkuratov@mail.com"
COPY autoreg.php /var/lib/zabbix/autoreg.php
EXPOSE 10051/TCP
ENTRYPOINT /usr/bin/docker-entrypoint.sh
