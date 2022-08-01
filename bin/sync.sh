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

rm -rf "${SOURCE_TMP}"
cp -lR "${SOURCE}" "${SOURCE_TMP}"
find "${SOURCE_TMP}" -type f | grep -f output/exclude | xargs -d "\n" rm -f

function generateRestore() {
  cat output/restore* | while read -r line; do
    echo "mv $(echo "${line//\'/\'\\\'\'}" | xargs printf "'${TARGET}/%s' ")"
  done
}

function generatePut() {
  echo "mput '${TARGET}'"
}

function generateDir() {
  sort output/mkdir | uniq | while read -r line; do
    echo "mkdir '${TARGET}/$line'"
  done
}

function generateMv() {
  cat output/mv* | while read -r line; do
    echo "mv $(echo "${line//\'/\'\\\'\'}" | xargs printf "'${TARGET}/%s' ")"
  done
}

function generateRm() {
  cat output/rm* | sort -r | while read -r line; do
    echo "rm '${TARGET}/$line'"
  done
}

error=$(generateRestore 2> /dev/null | docker-compose run rmapi | grep "Error:" | xargs -0 echo -n)
echo "$error"
[[ -n "$error" ]] && exit 1
rm -f output/restore* 2> /dev/null

error=$(generatePut 2> /dev/null | docker-compose run rmapi | grep "Error:" | xargs -0 echo -n)
echo "$error"
[[ -n "$error" ]] && exit 2

error=$(generateDir 2> /dev/null | docker-compose run rmapi | grep "Error:" | xargs -0 echo -n)
echo "$error"
[[ -n "$error" ]] && exit 3

error=$(generateMv 2> /dev/null | docker-compose run rmapi | grep "Error:" | xargs -0 echo -n)
echo "$error"
[[ -n "$error" ]] && exit 4
rm -f o output/mv* 2> /dev/null

error=$(generateRm 2> /dev/null | docker-compose run rmapi | grep "Error:" | xargs -0 echo -n)
echo "$error"
[[ -n "$error" ]] && exit 5
rm -f output/rm* 2> /dev/null

rm -r "${SOURCE_TMP}"
