-- phpMyAdmin SQL Dump
-- version 2.9.0.3
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tempo de Geração: Jun 08, 2012 as 04:27 PM
-- Versão do Servidor: 5.0.27
-- Versão do PHP: 5.2.0
-- 
-- Banco de Dados: `aaaaa`
-- 

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `caracoroa`
-- 

CREATE TABLE `caracoroa` (
  `id` int(100) NOT NULL auto_increment,
  `uid` int(100) NOT NULL,
  `did` int(100) NOT NULL,
  `cc` int(1) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `caracoroa`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_acc`
-- 

CREATE TABLE `fun_acc` (
  `id` int(100) NOT NULL auto_increment,
  `gid` int(100) NOT NULL default '0',
  `fid` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_acc`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_acoes`
-- 

CREATE TABLE `fun_acoes` (
  `id` int(255) NOT NULL auto_increment,
  `uid` varchar(255) NOT NULL,
  `who` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `acao` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_acoes`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_albums`
-- 

CREATE TABLE `fun_albums` (
  `id` int(50) NOT NULL auto_increment,
  `uid` varchar(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `cmt` varchar(200) NOT NULL,
  `time` varchar(100) NOT NULL,
  `vis` varchar(100) NOT NULL,
  `pontos` int(10) NOT NULL default '0',
  `senha` varchar(255) NOT NULL,
  `atul` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_albums`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_announcements`
-- 

CREATE TABLE `fun_announcements` (
  `id` int(100) NOT NULL auto_increment,
  `antext` varchar(200) NOT NULL,
  `clid` int(100) NOT NULL,
  `antime` int(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_announcements`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_ban`
-- 

CREATE TABLE `fun_ban` (
  `id` int(100) NOT NULL auto_increment,
  `uid` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `tipoban` varchar(100) NOT NULL,
  `tempo` varchar(100) NOT NULL,
  `motivo` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_ban`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_buddies`
-- 

CREATE TABLE `fun_buddies` (
  `id` int(100) NOT NULL auto_increment,
  `uid` int(100) NOT NULL default '0',
  `tid` int(100) NOT NULL default '0',
  `agreed` char(1) NOT NULL default '0',
  `reqdt` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_buddies`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_chat`
-- 

CREATE TABLE `fun_chat` (
  `id` int(99) NOT NULL auto_increment,
  `chatter` int(100) NOT NULL default '0',
  `who` int(100) NOT NULL default '0',
  `timesent` int(50) NOT NULL default '0',
  `msgtext` varchar(255) NOT NULL default '',
  `rid` int(99) NOT NULL default '0',
  `exposed` char(1) NOT NULL default '0',
  `para` varchar(255) NOT NULL,
  `acao` varchar(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_chat`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_chonline`
-- 

CREATE TABLE `fun_chonline` (
  `lton` int(15) NOT NULL default '0',
  `uid` int(100) NOT NULL default '0',
  `rid` int(99) NOT NULL default '0',
  PRIMARY KEY  (`lton`),
  UNIQUE KEY `username` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Extraindo dados da tabela `fun_chonline`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_clubmembers`
-- 

CREATE TABLE `fun_clubmembers` (
  `id` int(100) NOT NULL auto_increment,
  `uid` int(100) NOT NULL,
  `clid` int(100) NOT NULL,
  `accepted` char(1) NOT NULL default '0',
  `points` int(100) NOT NULL default '0',
  `joined` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_clubmembers`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_clubs`
-- 

CREATE TABLE `fun_clubs` (
  `id` int(100) NOT NULL auto_increment,
  `owner` int(100) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  `rules` blob NOT NULL,
  `logo` varchar(200) NOT NULL,
  `plusses` int(100) NOT NULL default '0',
  `created` int(100) NOT NULL default '0',
  `subdono` varchar(255) NOT NULL default '0',
  `tipo` varchar(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_clubs`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_cmt_a`
-- 

CREATE TABLE `fun_cmt_a` (
  `id` int(100) NOT NULL auto_increment,
  `uid` varchar(100) collate latin1_general_ci NOT NULL,
  `did` varchar(100) collate latin1_general_ci NOT NULL,
  `texto` varchar(100) collate latin1_general_ci NOT NULL,
  `cor` varchar(100) collate latin1_general_ci NOT NULL,
  `time` varchar(100) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_cmt_a`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_downloads`
-- 

CREATE TABLE `fun_downloads` (
  `id` int(255) NOT NULL auto_increment,
  `uid` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `visitas` varchar(255) NOT NULL,
  `categoria` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_downloads`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_fas`
-- 

CREATE TABLE `fun_fas` (
  `id` int(100) NOT NULL auto_increment,
  `vid` int(100) NOT NULL,
  `uid` int(100) NOT NULL,
  `star` int(100) NOT NULL,
  `perm` int(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_fas`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_fcats`
-- 

CREATE TABLE `fun_fcats` (
  `id` int(50) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `position` int(50) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_fcats`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_forums`
-- 

CREATE TABLE `fun_forums` (
  `id` int(50) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `position` int(50) NOT NULL default '0',
  `cid` int(100) NOT NULL default '0',
  `clubid` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_forums`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_fotos`
-- 

CREATE TABLE `fun_fotos` (
  `id` int(50) NOT NULL auto_increment,
  `uid` varchar(100) collate latin1_general_ci NOT NULL,
  `url` varchar(100) collate latin1_general_ci NOT NULL,
  `cmt` varchar(100) collate latin1_general_ci NOT NULL,
  `time` varchar(100) collate latin1_general_ci NOT NULL,
  `did` varchar(100) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_fotos`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_gbook`
-- 

CREATE TABLE `fun_gbook` (
  `id` int(100) NOT NULL auto_increment,
  `gbowner` int(100) NOT NULL default '0',
  `gbsigner` int(100) NOT NULL default '0',
  `gbmsg` blob NOT NULL,
  `dtime` int(100) NOT NULL default '0',
  `cor` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_gbook`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_ignore`
-- 

CREATE TABLE `fun_ignore` (
  `id` int(10) NOT NULL auto_increment,
  `name` int(99) NOT NULL default '0',
  `target` int(99) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_ignore`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_log`
-- 

CREATE TABLE `fun_log` (
  `id` int(255) NOT NULL auto_increment,
  `msg` text NOT NULL,
  `data` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_log`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_mequipe`
-- 

CREATE TABLE `fun_mequipe` (
  `id` int(100) NOT NULL auto_increment,
  `shout` varchar(150) NOT NULL default '',
  `shouter` int(100) NOT NULL default '0',
  `shtime` int(100) NOT NULL default '0',
  `cor` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_mequipe`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_mpot`
-- 

CREATE TABLE `fun_mpot` (
  `id` int(10) NOT NULL auto_increment,
  `ddt` varchar(20) NOT NULL default '',
  `dtm` varchar(20) NOT NULL default '',
  `ppl` int(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_mpot`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_notificacoes`
-- 

CREATE TABLE `fun_notificacoes` (
  `id` int(100) NOT NULL auto_increment,
  `text` blob NOT NULL,
  `byuid` int(100) NOT NULL default '0',
  `touid` int(100) NOT NULL default '0',
  `unread` char(1) NOT NULL default '1',
  `timesent` int(100) NOT NULL default '0',
  `starred` char(1) NOT NULL default '0',
  `reported` char(1) NOT NULL default '0',
  `cor` varchar(100) NOT NULL,
  `num` varchar(100) NOT NULL,
  `folderid` int(100) NOT NULL,
  `title` blob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_notificacoes`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_online`
-- 

CREATE TABLE `fun_online` (
  `id` int(10) NOT NULL auto_increment,
  `userid` int(100) NOT NULL default '0',
  `actvtime` int(100) NOT NULL default '0',
  `place` varchar(50) NOT NULL default '',
  `placedet` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_online`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_parceiros`
-- 

CREATE TABLE `fun_parceiros` (
  `id` int(255) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_parceiros`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_posts`
-- 

CREATE TABLE `fun_posts` (
  `id` int(100) NOT NULL auto_increment,
  `text` blob NOT NULL,
  `tid` int(100) NOT NULL default '0',
  `uid` int(100) NOT NULL default '0',
  `dtpost` int(100) NOT NULL default '0',
  `reported` char(1) NOT NULL default '0',
  `quote` int(100) NOT NULL default '0',
  `cor` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_posts`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_private`
-- 

CREATE TABLE `fun_private` (
  `id` int(100) NOT NULL auto_increment,
  `text` blob NOT NULL,
  `byuid` int(100) NOT NULL default '0',
  `touid` int(100) NOT NULL default '0',
  `unread` char(1) NOT NULL default '1',
  `timesent` int(100) NOT NULL default '0',
  `starred` char(1) NOT NULL default '0',
  `reported` varchar(2) NOT NULL default '0',
  `cor` varchar(100) NOT NULL,
  `num` varchar(100) NOT NULL,
  `folderid` int(100) NOT NULL,
  `title` blob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_private`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_rooms`
-- 

CREATE TABLE `fun_rooms` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `static` char(1) NOT NULL,
  `mage` int(10) NOT NULL,
  `chposts` int(100) NOT NULL,
  `perms` int(10) NOT NULL,
  `censord` char(1) NOT NULL default '1',
  `freaky` char(1) NOT NULL default '0',
  `lastmsg` int(100) NOT NULL,
  `clubid` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_rooms`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_search`
-- 

CREATE TABLE `fun_search` (
  `id` int(20) NOT NULL auto_increment,
  `svar1` varchar(50) NOT NULL default '',
  `svar2` varchar(50) NOT NULL default '',
  `svar3` varchar(50) NOT NULL default '',
  `svar4` varchar(50) NOT NULL default '',
  `svar5` varchar(50) NOT NULL default '',
  `stime` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_search`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_ses`
-- 

CREATE TABLE `fun_ses` (
  `id` varchar(100) NOT NULL default '',
  `uid` varchar(30) NOT NULL default '',
  `expiretm` int(100) NOT NULL default '0',
  UNIQUE KEY `id` (`id`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Extraindo dados da tabela `fun_ses`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_settings`
-- 

CREATE TABLE `fun_settings` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `value` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- 
-- Extraindo dados da tabela `fun_settings`
-- 

INSERT INTO `fun_settings` (`id`, `name`, `value`) VALUES 
(1, 'sesexp', '30'),
(2, 'Mon/09/Aug/2010 - 15:56', '15'),
(3, '4ummsg', '[b].vempostar. bora faturar .$. [topic=67].clik.[/topic] [topic=68].clik.[/topic][/b]'),
(4, 'Counter', '33941'),
(5, 'pmaf', ''),
(6, 'reg', '1'),
(7, 'fview', '0'),
(8, 'lastbpm', '2012-06-08'),
(9, 'banco', '2012-06-08'),
(10, 'cassino', '22'),
(18, 'vat', '1'),
(19, 'vdica', 'Qual o nome do site?'),
(20, 'vresposta', 'estaÃ§Ã£owap'),
(21, 'vultimo', '1');

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_shouts`
-- 

CREATE TABLE `fun_shouts` (
  `id` int(100) NOT NULL auto_increment,
  `shout` varchar(150) NOT NULL default '',
  `shouter` int(100) NOT NULL default '0',
  `shtime` int(100) NOT NULL default '0',
  `cor` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_shouts`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_smilies`
-- 

CREATE TABLE `fun_smilies` (
  `id` int(100) NOT NULL auto_increment,
  `scode` varchar(15) NOT NULL default '',
  `imgsrc` varchar(200) NOT NULL default '',
  `hidden` char(1) NOT NULL default '0',
  `cat` varchar(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `scode` (`scode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_smilies`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_spam`
-- 

CREATE TABLE `fun_spam` (
  `id` int(255) NOT NULL auto_increment,
  `txt` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_spam`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_topics`
-- 

CREATE TABLE `fun_topics` (
  `id` int(100) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `fid` int(100) NOT NULL default '0',
  `authorid` int(100) NOT NULL default '0',
  `text` blob NOT NULL,
  `pinned` char(1) NOT NULL default '0',
  `closed` char(1) NOT NULL default '0',
  `crdate` int(100) NOT NULL default '0',
  `views` int(100) NOT NULL default '0',
  `reported` char(1) NOT NULL default '0',
  `lastpost` int(100) NOT NULL default '0',
  `moved` char(1) NOT NULL default '0',
  `pollid` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_topics`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_trofeus`
-- 

CREATE TABLE `fun_trofeus` (
  `id` int(11) NOT NULL auto_increment,
  `who` varchar(255) default NULL,
  `motivo` varchar(155) default NULL,
  `hora` varchar(255) default NULL,
  `tipo` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_trofeus`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fun_users`
-- 

CREATE TABLE `fun_users` (
  `id` int(100) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `pass` varchar(60) NOT NULL default '',
  `tempon` varchar(50) NOT NULL default '0',
  `birthday` varchar(50) NOT NULL default '',
  `sex` char(1) NOT NULL default '',
  `location` varchar(100) NOT NULL default '',
  `perm` char(1) NOT NULL default '0',
  `posts` int(100) NOT NULL default '0',
  `plusses` int(100) NOT NULL default '0',
  `signature` varchar(100) NOT NULL default '',
  `avatar` varchar(100) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `recados` int(2) default '0',
  `browserm` varchar(50) NOT NULL default '',
  `ipadd` varchar(30) NOT NULL default '',
  `lastact` int(100) NOT NULL default '0',
  `regdate` int(100) NOT NULL default '0',
  `chmsgs` int(100) NOT NULL default '0',
  `shield` char(1) NOT NULL default '0',
  `budmsg` varchar(100) NOT NULL default '',
  `lastpnreas` varchar(100) NOT NULL default '',
  `lastplreas` varchar(100) NOT NULL default '',
  `shouts` int(100) NOT NULL default '0',
  `hvia` char(1) NOT NULL default '1',
  `lastvst` int(100) NOT NULL,
  `vip` varchar(100) NOT NULL,
  `banco` int(100) NOT NULL default '0',
  `shopssid` varchar(100) NOT NULL,
  `specialid` varchar(100) NOT NULL default '0',
  `tottimeonl` varchar(100) NOT NULL default '0',
  `humor` varchar(100) NOT NULL,
  `visitas` int(100) NOT NULL default '0',
  `numpm` varchar(100) default NULL,
  `rperm` varchar(255) NOT NULL default '0',
  `ruser` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `fun_users`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `loja`
-- 

CREATE TABLE `loja` (
  `id` int(100) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL default '0',
  `valor` int(5) NOT NULL default '0',
  `cat` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `loja`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `presentes`
-- 

CREATE TABLE `presentes` (
  `id` int(100) NOT NULL auto_increment,
  `pid` int(50) NOT NULL default '0',
  `uid` int(100) NOT NULL default '0',
  `eid` int(100) NOT NULL default '0',
  `msg` varchar(80) NOT NULL default '0',
  `data` int(50) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `presentes`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `virtual_pet`
-- 

CREATE TABLE `virtual_pet` (
  `uid` int(10) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `rodjen` int(20) NOT NULL,
  `tezina` int(10) NOT NULL,
  `ime` varchar(30) NOT NULL,
  `ziv` int(1) NOT NULL,
  `nahranjen` int(20) NOT NULL,
  `boja` varchar(15) NOT NULL,
  `igra` int(20) NOT NULL,
  `kupanje` int(20) NOT NULL,
  `smrt` int(20) NOT NULL,
  `raspolozenje` int(2) NOT NULL,
  `broj` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Extraindo dados da tabela `virtual_pet`
-- 


-- --------------------------------------------------------

-- 
-- Estrutura da tabela `visitantes`
-- 

CREATE TABLE `visitantes` (
  `id` int(255) NOT NULL auto_increment,
  `uid` varchar(255) collate latin1_general_ci NOT NULL,
  `vid` varchar(255) collate latin1_general_ci NOT NULL,
  `hora` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Extraindo dados da tabela `visitantes`
-- 

