class amsterdamphp::mysql {
    include mysql

    # Setup database user
    mysql::grant { "mysql_grant_${params::dbuser}":
        mysql_privileges => 'ALL',
        mysql_password => $params::dbpass,
        mysql_db => $params::dbname,
        mysql_user => $params::dbuser,
        mysql_host => 'localhost',
        require => Service['mysql']
    }
}
