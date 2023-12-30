# Install supervisor via Homebrew on Macos M1
# This will allow alwaysforward to start and run in the background on machine startup using the 
# builtin php server, which is fairly resource efficient and should be fine for most use cases.

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
ALWAYS_FORWARD_DIRECTORY="$(dirname "$SCRIPT_DIR")"

# Create server log
touch $ALWAYS_FORWARD_DIRECTORY/storage/logs/serve.log

brew install supervisor
wait

brew services start supervisor

# Create the alwaysforward config file
echo Creating alwaysforward supervisor config file...

mkdir /opt/homebrew/etc/supervisor.d/
touch /opt/homebrew/etc/supervisor.d/alwaysforward.ini

cat <<EOT >> /opt/homebrew/etc/supervisor.d/alwaysforward.ini
[program:fw-server]
process_name=%(program_name)s_%(process_num)02d
command=/opt/homebrew/bin/php -S localhost:8123 -t $ALWAYS_FORWARD_DIRECTORY/public
directory=$ALWAYS_FORWARD_DIRECTORY
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=$ALWAYS_FORWARD_DIRECTORY/storage/logs/serve.log  
EOT

echo Starting supervisor...
supervisorctl reread
supervisorctl update
supervisorctl restart all

supervisorctl status

