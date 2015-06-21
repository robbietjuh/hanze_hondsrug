#!/usr/bin/env bash

if [ "$#" -ne 1 ]; then
    echo "Specify host and port."
    echo "Usage: ./start-dev.sh <host>:<port>"
    exit 1
else
    if hash php 2>/dev/null; then
        php -S $1 -t ./src/main/
    else
        echo "You should have php installed for this work."
    fi
fi