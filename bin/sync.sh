#!/usr/bin/env bash

set -e

cd "$(dirname "$0")"

if [ -f .env ]; then
  set -a
  source .env
  set +a
fi

SOURCE=${SOURCE%/}
TARGET=${TARGET%/}
docker=/data

function generate() {
  cat output/restore* | while read -r line; do
    echo "mv $(echo "${line/\'/\'\\\'\'}" | xargs printf "'${TARGET}/%s' ")"
  done

  find "${SOURCE}" -type d | while read -r dir; do
    targetDir=${TARGET}${dir/${SOURCE}/}
    echo "mkdir '$targetDir'"
    find "$dir" -maxdepth 1 -type f | grep -vf output/exclude | while read -r file; do
      echo "put $(printf "%q" "${file/${SOURCE}/$docker}") '$targetDir'"
    done
  done

  while read -r line; do
    echo "mkdir '${TARGET}/$line'"
  done <output/mkdir

  cat output/mv* | while read -r line; do
    echo "mv $(echo "${line/\'/\'\\\'\'}" | xargs printf "'${TARGET}/%s' ")"
  done

  cat output/rm* | sort -r | while read -r line; do
    echo "rm '${TARGET}/$line'"
  done
}

generate 2> /dev/null | docker-compose run rmapi

rm -f output/restore* output/mv* output/rm* 2> /dev/null
