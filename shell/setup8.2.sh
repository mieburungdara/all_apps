#!/usr/bin/env bash
set -e

echo "🚀 Setting up PHP 8.2 environment..."

# Update repo & pasang Ondrej PPA
sudo add-apt-repository ppa:ondrej/php -y || true
sudo apt update

# Install PHP 8.2 + extension dasar
sudo apt install -y \
  php8.2 php8.2-cli php8.2-common \
  php8.2-mysql php8.2-xml php8.2-curl \
  php8.2-mbstring php8.2-zip

# Set default php ke versi 8.2 dari /usr/bin
sudo update-alternatives --install /usr/bin/php php /usr/bin/php8.2 82
sudo update-alternatives --set php /usr/bin/php8.2

# Fix PATH & alias agar PHP Codespaces tidak dipakai
if ! grep -q "/usr/bin" ~/.bashrc; then
  echo 'export PATH=/usr/bin:$PATH' >> ~/.bashrc
  echo 'alias php="/usr/bin/php"' >> ~/.bashrc
  echo 'alias composer="/usr/bin/php /usr/bin/composer"' >> ~/.bashrc
fi

# Reload shell config
source ~/.bashrc

echo "✅ PHP setup selesai!"
php -v
php -m | grep -E "pdo_mysql|mysqli|curl|xml|mbstring|zip"
