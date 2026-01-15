#!/bin/bash

ACF_URL="https://connect.advancedcustomfields.com/v2/plugins/download?s=web&p=pro&k=${ACF_PRO_KEY}"
WORKSPACE_PATH="/workspaces/api.helsingborg.se"
DEVCONTAINER_PATH="${WORKSPACE_PATH}/.devcontainer"

report_start() {
    echo -e "\n🚀 Starting setup script...\n"
}

move_config_files() {
    cp ${DEVCONTAINER_PATH}/config/wp-config/* ${WORKSPACE_PATH}/config/
    cp ${DEVCONTAINER_PATH}/config/.htaccess ${WORKSPACE_PATH}/.htaccess
}

install_wp() {
    wp core install --url=localhost:8080 --title="Helsingborg Api's [dev]" --admin_user=admin --admin_password=admin --admin_email=admin@helsingborg.se --allow-root --skip-email --skip-plugins --skip-themes --quiet
    echo "✅ WordPress installed."
}

# Do the following steps only if the key is not empty
install_and_activate_acf_pro() {
    if [ -z "$ACF_PRO_KEY" ]
    then
        echo "ACF PRO key is empty"
        exit 1
    fi

    curl -s -o /tmp/acf-pro.zip ${ACF_URL}
    unzip -oq /tmp/acf-pro.zip -d /tmp
    rm -f /tmp/acf-pro.zip
    cp -r /tmp/advanced-custom-fields-pro /var/www/html/wp-content/plugins/advanced-custom-fields-pro

    wp plugin activate advanced-custom-fields-pro --allow-root --url=localhost:8080 --skip-plugins --skip-themes --quiet
    echo "✅ Advanced Custom Fields Pro installed and activated."
}

report_done() {
    echo -e "\n🎉 Setup completed successfully!\n"
    echo -e "You can now access the WordPress site at: http://localhost:8080\n"
}

report_start
move_config_files
install_wp
install_and_activate_acf_pro
report_done