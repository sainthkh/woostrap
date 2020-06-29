#!/bin/bash

echo "Setting up database..."
wp db query < wp-content/plugins/data/data.sql

echo "Copying files..."
cp -r wp-content/plugins/data/uploads wp-content
