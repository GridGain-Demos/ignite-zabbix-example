# Articles
 - [RUS](https://habr.com/ru/company/gridgain/blog/530434/)
 - [ENG](https://www.gridgain.com/resources/blog/using-zabbix-monitor-apache-ignite-or-other-distributed-systems)
 
# How to prepare
1. If Docker and Docker Compose are not installed, download and install them.
2. From the repository, download the required files.
3. Go to the directory.
4. Start building the image: ```docker-compose -f docker-compose-zabbix.yml build```
5. Start the cluster and the monitoring server: ```docker-compose -f docker-compose-zabbix.yml up```
6. Wait for a few seconds. Now Zabbix is available on port 80.
# How to import templates:
1. Go to the Configuration->Templates->Import and import the zbx_export_templates.xml template (from the downloaded folder). Along with the template itself, the ‘Templates/Ignite autoregistration’ group will be added, this name will be used furthermore to add templates from this group to new nodes.
2. In each template that should be applied, specify the group created in the previous step. It already contains the “Template App Ignite JMX” template, I have added the “Template App Generic Java JMX” and “Template OS Linux by Zabbix agent” templates.
# Creating a script for agent auto-registration:
1. In the Zabbix interface go to the Configuration->Actions tab, in the drop-down list select Autoregistration actions and create a new action.
2. Create an action.
3. Name the action.
4. On the tab we can also specify conditions for adding a node.
5. If the conditions that were previously specified are met, this action creates a new node in Zabbix.
6. Add the launch of the autoreg.php script that will add the JMX port to the settings and apply the templates from the specified group to the passed node. For those who deploy a test cluster from the image, it will be located in the /var/lib/Zabbix folder; for those who install it on their own – in the repository specified above. In my case, it will run by the command ```php /var/lib/zabbix/autoreg.php {HOST.HOST} 'Templates/Ignite autoregistration' '{HOST.METADATA}'```.
