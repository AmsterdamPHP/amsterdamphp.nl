class amsterdamphp::symfony2 {
    # Initialize parameters
    file { "parameters.yml":
        path => "/vagrant/app/config/parameters.yml",
        source => "/vagrant/app/config/parameters.dev.yml",
        replace => "no",
        before  => Exec["composer-install"]
    }

    # Install vendors
    exec { "composer-install" :
        command => "/usr/bin/php /vagrant/bin/composer.phar install --dev",
        cwd     => "/vagrant/",
        creates => "/vagrant/app/bootstrap.php.cache",
        require => [ Exec['accept-github-ssh-hostkey'], Package["php"], Package["git"] ],
        timeout => 0,
        tries   => 10
    }
}
