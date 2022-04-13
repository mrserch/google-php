#!/bin/bash
# Copyright 2022 Google Inc.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

set -e

if [ "${BASH_DEBUG}" = "true" ]; then
    set -x
fi

if [ "${TEST_DIRECTORIES}" = "" ]; then
  TEST_DIRECTORIES="*"
fi

SKIP_DIRS=(
  appengine
  bigtable
  dialogflow
  iot
)

for dir in $(find $TEST_DIRECTORIES -type d -name src -not -path '/*'  -not -path 'appengine/*' -not -path '*/vendor/*' -exec dirname {} \;);
do
    if [[ " ${SKIP_DIRS[@]} " =~ " ${dir} " ]]; then
        echo "Skipping $dir (explicitly flagged to be skipped)"
        continue
    fi
    composer update --working-dir=$dir --ignore-platform-reqs
    echo "<?php require_once 'testing/sample_helpers.php';require_once '$dir/vendor/autoload.php';" > autoload.php
    neon="testing/phpstan/default.neon.dist"
    if [ -f "testing/phpstan/$dir.neon.dist" ]; then
        neon="testing/phpstan/$dir.neon.dist"
    fi
    echo "Running phpstan in $dir with config $neon"
    testing/vendor/bin/phpstan analyse $dir/src \
        --autoload-file=autoload.php \
        --configuration=$neon
done
