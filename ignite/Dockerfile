FROM apacheignite/ignite
LABEL maintainer="akkuratov@mail.com"
RUN apk update && apk add zabbix-agent
COPY zabbix_agentd.conf /etc/zabbix/zabbix_agentd.conf
EXPOSE 10050/TCP
CMD /usr/sbin/zabbix_agentd && $IGNITE_HOME/run.sh
