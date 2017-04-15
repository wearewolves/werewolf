#!/bin/bash

COMMIT=`git rev-list --tags --skip=1  --max-count=1`
BEFORE_TAG=`git describe --abbrev=0 --tags $COMMIT`
`git diff $BEFORE_TAG $3 --name-only |  xargs tar -zcf release.tar.gz`
`mkdir release`
`tar -xvzf release.tar.gz -C release`

gulp deploy --user $1 --password $2
