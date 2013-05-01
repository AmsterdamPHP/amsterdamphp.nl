class amsterdamphp::setup {

    # Update apt
    exec { "apt-update":
        command => "/usr/bin/apt-get update"
    }
    Exec["apt-update"] -> Package <| |>

    # Install default packages
    $default_packages = [ "curl", "git", "vim", "nano", "acl", "sendmail" ]
    package { $default_packages:
        ensure => present
    }

    # Install ruby gems
    $default_gems = [ "capistrano", "capistrano-ext", "railsless-deploy" ]
    package { $default_gems:
        ensure => present,
        provider => 'gem'
    }

    # Force-approve the github SSH host key
    exec { "accept-github-ssh-hostkey":
        command => 'ssh -o StrictHostKeyChecking=no git@github.com || true',
        user => 'vagrant',
        creates => '/home/vagrant/.ssh/known_hosts'
    }

    # Change login working directory for vagrant
    exec { "change-vagrant-login-wd":
        command => 'echo "cd /vagrant" >> /home/vagrant/.bashrc',
        user => 'vagrant',
        unless => 'grep "cd /vagrant" /home/vagrant/.bashrc'
    }
}
