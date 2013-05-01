class amsterdamphp::web {
    # Setup apache
    include apache

    apache::module { 'rewrite': }

    exec { "apache-user-group-change" :
        command => "sed -i 's/APACHE_RUN_USER=www-data/APACHE_RUN_USER=vagrant/ ; s/APACHE_RUN_GROUP=vagrant/APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars",
        onlyif  => "grep -c 'APACHE_RUN_USER=www-data' /etc/apache2/envvars",
        require => Package["apache"],
        notify  => Service['apache'],
    }

    # Remove default site
    file { "/etc/apache2/sites-enabled/000-default":
        ensure => "absent",
        require => Package["apache"]
    }

    # Setup site vhost
    apache::vhost { $params::host:
        docroot => '/vagrant/web',
        template => 'amsterdamphp/vhost.conf.erb'
    }

    # Setup PHP
    class { 'php':
        package_pear => 'php-pear'
    }

    php::module {'mysql': }
    php::module {'sqlite': }
    php::module {'curl': }
    php::module {'gd': }
    php::pecl::module  { 'xdebug': }

    php::pear::module { 'PHPUnit':
        repository => 'pear.phpunit.de',
        use_package => false
    }

    augeas { 'set-php-ini-values-cli':
        context => '/files/etc/php5/cli/php.ini',
        changes => [
            'set PHP/error_reporting "E_ALL"',
            'set PHP/display_errors On',
            'set PHP/display_startup_errors On',
            'set Date/date.timezone Europe/Amsterdam',
        ],
        require => Package['php'],
    }

    augeas { 'set-php-ini-values-apache2':
        context => '/files/etc/php5/apache2/php.ini',
        changes => [
            'set PHP/error_reporting "E_ALL"',
            'set PHP/display_errors On',
            'set PHP/display_startup_errors On',
            'set PHP/html_errors On',
            'set Date/date.timezone Europe/Amsterdam',
        ],
        require => Package['php'],
        notify  => Service['apache'],
    }

    file { "/var/lib/php/session":
        owner   => "root",
        group   => "vagrant",
        mode    => 0770,
        require => Package["php"],
    }
}
