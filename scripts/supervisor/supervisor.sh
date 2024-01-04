# Install supervisor via Homebrew on Macos M1
# This will allow alwaysforward to start and run in the background on machine startup using the 
# builtin php server, which is fairly resource efficient and should be fine for most use cases.

ALWAYS_FORWARD_DIRECTORY=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && cd .. && pwd )

# Create server log
touch $ALWAYS_FORWARD_DIRECTORY/storage/logs/serve.log

brew install supervisor
wait

brew services start supervisor

# Create the alwaysforward config file
echo Creating alwaysforward supervisor config file...

mkdir /opt/homebrew/etc/supervisor.d/
touch /opt/homebrew/etc/supervisor.d/alwaysforward.ini

# Make local copy of the configuration file
rm ./scripts/supervisor/alwaysforward.ini
touch ./scripts/supervisor/alwaysforward.ini

# Write the configuration file
cat <<EOT >> ./scripts/supervisor/alwaysforward.ini
[program:fw-server]
process_name=%(program_name)s_%(process_num)02d
command=/opt/homebrew/bin/php -S localhost:8124 -t $ALWAYS_FORWARD_DIRECTORY/public
directory=$ALWAYS_FORWARD_DIRECTORY
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=$ALWAYS_FORWARD_DIRECTORY/storage/logs/serve.log  
EOT

# Copy the config file to the supervisor.d directory
cp ./scripts/supervisor/alwaysforward.ini /opt/homebrew/etc/supervisor.d/alwaysforward.ini

echo Starting supervisor...
supervisorctl reread
supervisorctl update
supervisorctl restart all

supervisorctl status

