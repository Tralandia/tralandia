#!/bin/sh

# Path to this script's directory
dir=$(cd `dirname $0` && pwd)

runnerScript="$dir/../vendor/nette/tester/Tester/tester.php"


php "$runnerScript" -j 20 "$@"
