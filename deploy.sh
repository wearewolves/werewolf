#!/bin/bash

COMMIT=`git rev-list --tags --skip=1  --max-count=1`
BEFORE_TAG=`git describe --abbrev=0 --tags $COMMIT`
FILELIST=`git diff $BEFORE_TAG $3 --name-only`

gulp deploy --user $1 --password $2 --filelist "$FILELIST"