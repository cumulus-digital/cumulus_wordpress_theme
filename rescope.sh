#!/bin/bash
if ! command -v php-scoper &> /dev/null; then
	echo "php-scoper must be installed and available in your PATH."
	exit
fi
if ! command -v composer &> /dev/null; then
	echo "composer must be installed and available in your PATH."
	exit
fi

echo "Setting up composer vendors without dev"
composer install --no-scripts --no-dev --prefer-dist
echo "Scoping..."
# Disabling E_DEPRECATED for now, php-scoper not compatible with php 8.5
php -d error_reporting="E_ALL & ~E_DEPRECATED" $(which php-scoper) add-prefix --config scoper.inc.php --output-dir assets/prod/composer --force -vvv
echo "Updating vendor_keep autoloader"
composer dump-autoload --working-dir assets/prod/composer --classmap-authoritative
# Composer always maps the unprefixed Composer\InstalledVersions to our scoped
# InstalledVersions.php. Our prepended loader then hijacks other plugins' lookups
# and re-includes the file, redeclaring the scoped class (fatal). See php-scoper#1034.
echo "Removing unprefixed Composer\\InstalledVersions from classmap"
status=0
for f in assets/prod/composer/vendor/composer/autoload_classmap.php assets/prod/composer/vendor/composer/autoload_static.php; do
	grep -vF "'Composer\\\\InstalledVersions' =>" "$f" > "$f.tmp" && mv "$f.tmp" "$f"
	if grep -qF "InstalledVersions" "$f"; then
		echo "ERROR: InstalledVersions still present in $f"
		status=1
	fi
done
echo "Reinstalling dev"
composer install --no-scripts --prefer-dist
exit $status