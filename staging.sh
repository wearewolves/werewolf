#!/bin/bash

git diff HEAD~1 HEAD --name-only |  xargs tar -zcf release.tar.gz
mkdir release
tar -xvzf release.tar.gz -C release
ls release
gulp staging --user $1 --password $2
