#!/bin/sh
sudo chmod 777 /dev/ttyUSB0

sudo dl-fldigi/src/dl-fldigi --hab --xmlrpc-server-port 7236
