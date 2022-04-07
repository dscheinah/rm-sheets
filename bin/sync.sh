#!/usr/bin/env bash

set -e

cd "$(dirname "$0")"

if [ -f .env ]; then
  set -a
  source .env
  set +a
fi

SOURCE=${SOURCE%/}
SOURCE_TMP=${SOURCE_TMP%/}
TARGET=${TARGET%/}

cp -lR "${SOURCE}" "${SOURCE_TMP}"
find "${SOURCE_TMP}" -type f | grep -f output/exclude | xargs -d "\n" rm -f

function generate() {
  cat output/restore* | while read -r line; do
    echo "mv $(echo "${line//\'/\'\\\'\'}" | xargs printf "'${TARGET}/%s' ")"
  done

  echo "mput /"

  sort output/mkdir | uniq | while read -r line; do
    echo "mkdir '${TARGET}/$line'"
  done

  cat output/mv* | while read -r line; do
    echo "mv $(echo "${line//\'/\'\\\'\'}" | xargs printf "'${TARGET}/%s' ")"
  done

  cat output/rm* | sort -r | while read -r line; do
    echo "rm '${TARGET}/$line'"
  done
}

error=$(generate 2> /dev/null | docker-compose run rmapi | grep "Error:")

[[ -z "$error" ]] && rm -f output/restore* output/mv* output/rm* 2> /dev/null

rm -r "${SOURCE_TMP}"
