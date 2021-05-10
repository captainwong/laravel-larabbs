#/bin/bash

set -e


if [ "$1" = "" ]; then
branch='master'
else
branch=$1
fi

if [ "$2" = "" ]; then
push=0
else
push=1
fi


if [[ $push -ne 0 ]]; then

if [ "$3" = "" ]; then
change='update'
else
change=$3
fi

git aa
git cm "${change}"
git push ali ${branch}
fi

ssh -t vagrant@192.168.1.136 -p 22222 "cd /home/vagrant/code/larabbs && git pull origin ${branch}"




