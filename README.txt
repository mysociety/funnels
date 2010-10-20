Funnels
=======
A Piwik (http://piwik.org/) plugin that allows the definition, analysis and visualisation of funnels associated with goals.

Credits
=======
German translation by Uwe Schulz

Changelog
=========

Funnels 0.2 - 20/10/10
----------------------
* Updated example URL in funnel creation/edit to be absolute
* Fixed primary key on log_funnel_step table - missing column idfunnel was preventing one action from being recorded as a step in more than one funnel. To fix manually, run the following against your database:
	ALTER TABLE [piwik_table_prefix]_log_funnel_step DROP PRIMARY KEY, ADD PRIMARY KEY(`idvisit`, `idfunnel`, `idstep`);

Funnels 0.1  
-----------
Initial release