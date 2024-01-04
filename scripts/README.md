# Always Forward Developer Scripts

The following scripts contain various helpers, install scripts, and cli commands. 

## Supervisor
The least dumb way to keep the app running on my computer is by using [supervisor](http://supervisord.org/). This is a fairly common approach to running tasks in the background, and is even recommended by Laravel for running background jobs. 

I have created an install script that can install supervisor on your Mac using Homebrew under `./supervisor/supervisor.sh`. If you don't have a Mac or would like to install another way, that's fine too. Simply install supervisor however you would like, and then paste the following into your supervisord config file, replacing the `$ALWAYS_FORWARD_DIRECTORY` with the actual path to your directory:

```bash
[program:fw-server]
process_name=%(program_name)s_%(process_num)02d
command=/opt/homebrew/bin/php -S localhost:8124 -t $ALWAYS_FORWARD_DIRECTORY/public
directory=$ALWAYS_FORWARD_DIRECTORY
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=$ALWAYS_FORWARD_DIRECTORY/storage/logs/serve.log  
```

Setting this up to run in the background has the added benefit of allowing us to run our electron desktop application under the `./desktop` folder. The electron app is simply a Chromium wrapper that will open http://localhost:8124, which should already be running. This is the least frustrating way of running Laravel with Electron. Otherwise, you have to start packaging php binaries to spin up a php server on start, and I have literally never, after months worth of attempts, gotten that to work for me. 

## Aliases
The `./aliases` file can be appended to your shell profile with:
```bash
source path/to/alwaysforward/aliases
```

This will add a heap of app helpers and CLI tools. Feel free to add whatever you want.

## Dev
The `./dev.sh` file is a simple developer script that will concurrently run `php artisan serve` and `yarn dev`

## Start
The `./start.sh` file is a simple script that will build the app, start a php server, and then open a browser to http://localhost:8124. 

You can pass a --quit flag to this script to kill the php server. 