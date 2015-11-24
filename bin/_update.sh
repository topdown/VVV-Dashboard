#!/bin/bash

echo "Checking status ............ "

if [ -n "$(git status --porcelain)" ]; then
  echo "You have changes so we can't update. You need to either push your changes, stash or reset manually.";
else
   echo "Performing a git pull to update to the latest version ............ "

 # Do git pull here.

	echo "Update complete ..."
fi



