Update
======

Run the following command in the command line (after installing npm):

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
npm install -g npm-check-updates && ncu -u && npm update --upgradeAll && npm dedupe
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

 

Install
=======

Install grunt and all required dependencies

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
npm install -g grunt-cli && npm install
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

 

Go to `“hidden/config”` and change the `“.php”` extension to `“.sec.php”` and
insert correct values. Then rename the `“.ftppass.sample”` in the root directory
to `”.ftpapss”` and insert the FTP server’s username and password. Rename the
`”ftp.json”` in the root directory to `”ftp.sec.json”` and insert valid values
there as well.

Deploy
======

Use the `deploy` task provided. (e.g. with `grunt deploy`)

Developing
==========

When coding you have to run the `default` task in background. (e.g. with `grunt
default`)

 

License
=======

See `LICENSE` file in repo.
