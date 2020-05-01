#!/usr/bin/env bash

if ! [ -x "$(command -v i18next-conv)" ]; then
  echo 'Error: i18next-conv is not installed.' >&2
  echo 'Run `npm install i18next-conv -g` and run the script again'
  exit 1
fi

php converter.php
locales=(en de es fr nl nn nb pt)
en_suffixes="AU BZ CA GB IE IN JM MY NZ PH SG TT US ZA ZW"
all_suffixes=("AU BZ CA GB IE IN JM MY NZ PH SG TT US ZA ZW" "AT CH DE LI LU" "AR BO CL CO CR DO EC ES GT HN MX NI PA PE PR PY SV US UY VE" "BE CA CH FR LU" "BE NL" "NO" "NO" "BR PT")


for index in ${!locales[*]}
do
  locale=${locales[$index]}
  suffixes=(${all_suffixes[$index]})
  i18next-conv --ctxSeparator ! -k . -l $locale -s converted/$locale.json -t ../workshop-butler/languages/wsbintegration-${locale}.po
  i18next-conv --ctxSeparator ! -k . -l $locale -s converted/$locale.json -t ../workshop-butler/languages/wsbintegration-${locale}.mo
  for j in ${!suffixes[*]}
  do
    suffix=${suffixes[$j]}
    cp ../workshop-butler/languages/wsbintegration-${locale}.po ../workshop-butler/languages/wsbintegration-${locale}_${suffix}.po
    cp ../workshop-butler/languages/wsbintegration-${locale}.mo ../workshop-butler/languages/wsbintegration-${locale}_${suffix}.mo
  done
done
