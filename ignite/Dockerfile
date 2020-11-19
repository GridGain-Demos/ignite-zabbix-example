FROM docker pull apacheignite/ignite:2.8.1
LABEL maintainer="akkuratov@mail.com"
RUN apk update && apk add zabbix-agent
COPY zabbix_agentd.conf /etc/zabbix/zabbix_agentd.conf
EXPOSE 10050/TCP
CMD /usr/sbin/zabbix_agentd && $IGNITE_HOME/run.sh
