# Monitoramento-PON-Huawei

##### Em caso de dúvida, sugestão ou dificuldade junte-se a nós no Grupo do Telegram [Lsnetworks Public](https://t.me/lsnetworks_public)

Este template foi testado em todas versões do zabbix acima de 5.0 e funcionou normalmente.

# Requisitos

#### 0 - Zabbix-agent instalado.
#### 1 - PHP >= 5.5.
#### 3 - Assista o tutorial no [YouTube](https://www.youtube.com/watch?v=bnLK9W-o8u8).
#### 4 - Se inscrever no nosso canal no [YouTube](https://www.youtube.com/channel/UCy6NZNSgQ6sZWEz4VCAngog).
#### 5 - Dar uma *estrelinha* neste projeto.

# Comandos usados no video

#### 0 - mkdir /etc/zabbix/scripts
#### 1 - chown zabbix:zabbix /etc/zabbix/scripts/
#### 2 - cd /etc/zabbix/scripts/
#### 3 - git clone https://github.com/lsnetworks41/Monitoramento-PON-Huawei
#### 4 - rm Monitoramento-PON-Huawei/template/zbx_export_templates.xml
#### 5 - mv Monitoramento-PON-Huawei/template/files./onts.php /etc/zabbix/scripts/; chmod +x /etc/zabbix/scripts/onts.php 
#### 6 - rm -R Monitoramento-PON-Huawei/
#### 7 - php onts.php *community_OLT* *ip_OLT*
#### 8 - nano /etc/zabbix/zabbix_agentd.conf  ***OBS:***( Procurar timeout e editar para 30 em seguida e procurar por # UserParameter= e adicionar os comandos )
  <pre>
    UserParameter=data_pon[*],/etc/zabbix/scripts/onts.php $1 $2
    UserParameter=qt_total[*],echo  $(snmpwalk -v 2c -c $1 $2 .1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15.$3 | wc -l)
    UserParameter=qt_on[*],echo  $(snmpwalk -v 2c -c $1 $2 .1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15.$3 | grep 'INTEGER: 1' | wc -l)
    UserParameter=qt_off[*],echo $(snmpwalk -v 2c -c $1 $2 .1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15.$3 | grep 'INTEGER: 2' | wc -l)
  </pre>

#### 9 - systemctl restart zabbix-agent.service;systemctl status zabbix-agent.service
