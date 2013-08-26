group { 'puppet': ensure => present }
Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }
File { owner => 0, group => 0, mode => 0644 }

class {'apt':
  always_apt_update => true,
}

Class['::apt::update'] -> Package <|
    title != 'python-software-properties'
and title != 'software-properties-common'
|>

apt::source { 'packages.dotdeb.org':
  location          => 'http://packages.dotdeb.org',
  release           => $lsbdistcodename,
  repos             => 'all',
  required_packages => 'debian-keyring debian-archive-keyring',
  key               => '89DF5277',
  key_server        => 'keys.gnupg.net',
  include_src       => true
}

if $lsbdistcodename == 'squeeze' {
  apt::source { 'packages.dotdeb.org-php54':
    location          => 'http://packages.dotdeb.org',
    release           => 'squeeze-php54',
    repos             => 'all',
    required_packages => 'debian-keyring debian-archive-keyring',
    key               => '89DF5277',
    key_server        => 'keys.gnupg.net',
    include_src       => true
  }
}

package { 'apache2-mpm-prefork':
  ensure => 'installed',
  notify => Service['apache'],
}

class { 'puphpet::dotfiles': }

package { [
    'vim',
    'curl',
    'git-core'
  ]:
  ensure  => 'installed',
}

class { 'apache': }

apache::dotconf { 'custom':
  content => 'EnableSendfile Off',
}

apache::module { 'rewrite': }

apache::vhost { 'amsterdamphp.dev':
  server_name   => 'amsterdamphp.dev',
  serveraliases => [
    'site.amsterdamphp.dev'
  ],
  docroot       => '/var/www/web/',
  port          => '80',
  env_variables => [
    'APP_ENV dev'
  ],
  priority      => '1',
}

class { 'php':
  service             => 'apache',
  service_autorestart => false,
  module_prefix       => '',
}

php::module { 'php5-mysql': }
php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }

class { 'php::devel':
  require => Class['php'],
}

class { 'php::pear':
  require => Class['php'],
}



$xhprofPath = '/var/www/xhprof'

php::pecl::module { 'xhprof':
  use_package     => false,
  preferred_state => 'beta',
}

if !defined(Package['git-core']) {
  package { 'git-core' : }
}

vcsrepo { $xhprofPath:
  ensure   => present,
  provider => git,
  source   => 'https://github.com/facebook/xhprof.git',
  require  => Package['git-core']
}

file { "${xhprofPath}/xhprof_html":
  ensure  => 'directory',
  owner   => 'vagrant',
  group   => 'vagrant',
  mode    => '0775',
  require => Vcsrepo[$xhprofPath]
}

composer::run { 'xhprof-composer-run':
  path    => $xhprofPath,
  require => [
    Class['composer'],
    File["${xhprofPath}/xhprof_html"]
  ]
}

apache::vhost { 'xhprof':
  server_name => 'xhprof',
  docroot     => "${xhprofPath}/xhprof_html",
  port        => 80,
  priority    => '1',
  require     => [
    Php::Pecl::Module['xhprof'],
    File["${xhprofPath}/xhprof_html"]
  ]
}


class { 'xdebug':
  service => 'apache',
}

class { 'composer':
  require => Package['php5', 'curl'],
}

puphpet::ini { 'xdebug':
  value   => [
    'xdebug.default_enable = 1',
    'xdebug.remote_autostart = 0',
    'xdebug.remote_connect_back = 1',
    'xdebug.remote_enable = 1',
    'xdebug.remote_handler = "dbgp"',
    'xdebug.remote_port = 9000'
  ],
  ini     => '/etc/php5/conf.d/zzz_xdebug.ini',
  notify  => Service['apache'],
  require => Class['php'],
}

puphpet::ini { 'php':
  value   => [
    'date.timezone = "Europe/Amsterdam"'
  ],
  ini     => '/etc/php5/conf.d/zzz_php.ini',
  notify  => Service['apache'],
  require => Class['php'],
}

puphpet::ini { 'custom':
  value   => [
    'display_errors = On',
    'error_reporting = -1'
  ],
  ini     => '/etc/php5/conf.d/zzz_custom.ini',
  notify  => Service['apache'],
  require => Class['php'],
}


class { 'mysql::server':
  config_hash   => { 'root_password' => 'root' }
}


class { 'phpmyadmin':
  require => [Class['mysql::server'], Class['mysql::config'], Class['php']],
}

apache::vhost { 'phpmyadmin':
  server_name => 'phpmyadmin',
  docroot     => '/usr/share/phpmyadmin',
  port        => 80,
  priority    => '10',
  require     => Class['phpmyadmin'],
}

class { 'redis':
    version => '2.6.5',
}
