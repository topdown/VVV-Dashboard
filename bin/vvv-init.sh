# Init script for VVV Auto Bootstrap Demo 1

echo "Commencing VVV Demo 1 Setup"

# Make a database, if we don't already have one
echo "Creating database (if it's not already there)"
mysql -u root --password=root -e "CREATE DATABASE IF NOT EXISTS vvv_demo_1"
mysql -u root --password=root -e "GRANT ALL PRIVILEGES ON vvv_demo_1.* TO wp@localhost IDENTIFIED BY 'wp';"

# Download WordPress
if [ ! -d htdocs ]
then
	echo "Installing WordPress using WP CLI"
	mkdir htdocs
	cd htdocs
	wp core download
	wp core config --dbname="vvv_demo_1" --dbuser=wp --dbpass=wp --dbhost="localhost"
	wp core install --url=vvv-demo-1.dev --title="VVV Bootstrap Demo 1" --admin_user=admin --admin_password=password --admin_email=demo@example.com
	cd ..
fi

# The Vagrant site setup script will restart Nginx for us

echo "VVV Demo 1 site now installed";

