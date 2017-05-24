#!/bin/bash

git diff HEAD~1 HEAD --name-only |  xargs tar -ztvcf release.tar.gz
mkdir release
tar -xtvzf release.tar.gz -C release

gulp staging --user $1 --password $2
