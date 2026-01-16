#!/bin/bash

ACF_URL="https://connect.advancedcustomfields.com/v2/plugins/download?s=web&p=pro&k=${ACF_PRO_KEY}"
WORKSPACE_PATH="/workspaces/api.helsingborg.se"
DEVCONTAINER_PATH="${WORKSPACE_PATH}/.devcontainer"
PORT=8080

report_start() {
    echo -e "\n🚀 Starting setup script...\n"
}

install_dependencies() {
    if [ -d "vendor" ]; then
        read -p "Composer dependencies already installed. Reinstall? (yes/[no]): " confirm
        if [ "$confirm" != "yes" ]; then
            echo "⏩ Skipping Composer install."
            return
        fi
    fi
    composer i --quiet
    echo "✅ Composer dependencies installed."
    php ./build.php > /dev/null 2>&1
    echo "✅ Build script executed."
}

move_config_files() {
    # Check if any wp-config files already exist in the destination
    config_files_exist=false
    for f in ${DEVCONTAINER_PATH}/config/wp-config/*; do
        dest_file="${WORKSPACE_PATH}/config/$(basename $f)"
        if [ -e "$dest_file" ]; then
            config_files_exist=true
            break
        fi
    done
    htaccess_exists=false
    if [ -e "${WORKSPACE_PATH}/.htaccess" ]; then
        htaccess_exists=true
    fi

    if [ "$config_files_exist" = true ] || [ "$htaccess_exists" = true ]; then
        read -p "Some config files already exist. Overwrite? (yes/[no]): " confirm
        if [ "$confirm" != "yes" ]; then
            echo "⏩ Skipping moving config files."
            return
        fi
    fi
    cp ${DEVCONTAINER_PATH}/config/wp-config/* ${WORKSPACE_PATH}/config/
    cp ${DEVCONTAINER_PATH}/config/.htaccess ${WORKSPACE_PATH}/.htaccess
}

setup_cache_directory() {
    mkdir -p ${WORKSPACE_PATH}/wp-content/uploads/cache
    mkdir -p ${WORKSPACE_PATH}/wp-content/uploads/cache/blade-cache
    sudo chmod -R 766 ${WORKSPACE_PATH}/wp-content/uploads/cache/blade-cache
    echo "✅ Cache directory set up."
}

install_wp() {
    if wp core is-installed --allow-root --quiet; then
        read -p "WordPress is already installed. Do you want to reinstall it? (yes/[no]): " confirm
        if [ "$confirm" != "yes" ]; then
            echo "⏩ Skipping WordPress installation."
            return
        fi
    fi
    wp core install --url=localhost:${PORT} --title="Helsingborg Api's [dev]" --admin_user=admin --admin_password=admin --admin_email=admin@helsingborg.se --allow-root --skip-email --skip-plugins --skip-themes --quiet
    echo "✅ WordPress installed."
}

# Do the following steps only if the key is not empty
install_and_activate_acf_pro() {
    if [ -z "$ACF_PRO_KEY" ]
    then
        echo "ACF PRO key is empty"
        exit 1
    fi

    ACF_PLUGIN_PATH="/var/www/html/wp-content/plugins/advanced-custom-fields-pro"
    if [ -d "$ACF_PLUGIN_PATH" ]; then
        read -p "ACF Pro is already installed. Reinstall? (yes/[no]): " confirm
        if [ "$confirm" != "yes" ]; then
            echo "⏩ Skipping ACF Pro installation."
            wp plugin activate advanced-custom-fields-pro --allow-root --url=localhost:${PORT} --skip-plugins --skip-themes --quiet
            return
        fi
        rm -rf "$ACF_PLUGIN_PATH"
    fi

    curl -s -o /tmp/acf-pro.zip ${ACF_URL}
    unzip -oq /tmp/acf-pro.zip -d /tmp
    rm -f /tmp/acf-pro.zip
    cp -r /tmp/advanced-custom-fields-pro "$ACF_PLUGIN_PATH"

    wp plugin activate advanced-custom-fields-pro --allow-root --url=localhost:${PORT} --skip-plugins --skip-themes --quiet
    echo "✅ Advanced Custom Fields Pro installed and activated."
}

report_done() {
    echo -e "\n🎉 Setup completed successfully!\n"
    echo -e "You can now access the WordPress site at: http://localhost:8080\n"
}

report_start
install_dependencies
move_config_files
setup_cache_directory
install_wp
install_and_activate_acf_pro
report_done