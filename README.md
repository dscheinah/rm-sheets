# rm-sheets

A simple tool and collection of scripts I use to manage files on the reMarkable.

To deploy in production, use the templates in the bin folder. Also be aware to clone with submodules to get the source of https://github.com/juruen/rmapi.

* copy **.env.dist** to **.env**
  * _SOURCE_ should point to the directory of available files
  * _TARGET_ is the path on the reMarkable to sync to
* copy **docker-compose.yml.dist** to **docker-compose.yml**
  * extend the configuration to match your needs
  * run `docker-compose run rmapi` once to login
* **sync.sh** will do the complete sync

I run `sync.sh` by cron and use https://rclone.org/ and SMB to fill the _SOURCE_ directory.
