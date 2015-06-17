<?php

  //setup to create table if not at instalation

  $installer = $this;
  $installer->startSetup();

  $installer->run("
    DROP TABLE IF EXISTS {$this->getTable('np6_mailperformance')};

    CREATE TABLE {$this->getTable('np6_mailperformance')} (
      `id_magento` int(11),
      `id_mailperf` VARCHAR(8),
      PRIMARY KEY (`id_magento`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8
  ");

  $installer->endSetup();