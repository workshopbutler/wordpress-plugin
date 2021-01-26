#!/usr/bin/env bash

TMP_DIR=".tmp.converted"

trap exit ERR

if ! [ -x "$(command -v i18next-conv)" ]; then
  echo 'Error: i18next-conv is not installed.' >&2
  echo 'Run `npm install i18next-conv -g` and run the script again'
  exit 1
fi

if ! [ -x "$(command -v php)" ]; then
  echo 'Error: php is not installed.' >&2
  exit 1
fi

if [ -d $TMP_DIR ]; then
  echo "Error: tmp direcory $TMP_DIR already exists." >&2
  echo "Remove $TMP_DIR directory first"
  exit 1
fi

mkdir $TMP_DIR

php converter.php "locales" $TMP_DIR

locales=(en de es fr nl nn nb pt)
locale_suffixes=(
  "AU BZ CA GB IE IN JM MY NZ PH SG TT US ZA ZW"
  "AT CH DE LI LU"
  "AR BO CL CO CR DO EC ES GT HN MX NI PA PE PR PY SV US UY VE"
  "BE CA CH FR LU"
  "BE NL"
  "NO"
  "NO"
  "BR PT"
)


for index in ${!locales[*]}
do
  locale=${locales[$index]}
  suffixes=(${locale_suffixes[$index]})
  i18next-conv --ctxSeparator ! -k . -l $locale -s $TMP_DIR/$locale.json -t $TMP_DIR/$locale.po
  i18next-conv --ctxSeparator ! -k . -l $locale -s $TMP_DIR/$locale.json -t $TMP_DIR/$locale.mo
  for j in ${!suffixes[*]}
  do
    suffix=${suffixes[$j]}
    cp $TMP_DIR/$locale.po ../workshop-butler/languages/wsbintegration-${locale}_${suffix}.po
    cp $TMP_DIR/$locale.mo ../workshop-butler/languages/wsbintegration-${locale}_${suffix}.mo
  done
done

rm -r $TMP_DIR
