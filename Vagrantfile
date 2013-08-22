Vagrant::Config.run do |config|
    # Ubuntu quantal 64 bit
    config.vm.box = 'quantal64-cloud'
    config.vm.box_url = 'http://cloud-images.ubuntu.com/quantal/current/quantal-server-cloudimg-vagrant-amd64-disk1.box'

    # Enable GUI to debug vagrant
    #config.vm.boot_mode = :gui

    # Increase memory to 1.5 gigs
    config.vm.customize ["modifyvm", :id, "--memory", 1536]

    # Host <=> child filesystem sync is VERY slow on *NIX. This makes sure we enable
    # NFS to speed this up when we're not on Windows. This requires root access.
    processor, platform, *rest = RUBY_PLATFORM.split("-")
    use_nfs = platform != 'mingw32'
    config.vm.share_folder("v-root", "/vagrant", ".", :nfs => use_nfs)

    # Enable SSH agent forwarding
    config.ssh.forward_agent = true

    config.vm.define :amsterdamphp do |amsterdamphp_config|
        # Hostname. This needs to point to the IP address below
        amsterdamphp_config.vm.host_name = "amsterdamphp.dev"

        # IP address of the vm
        amsterdamphp_config.vm.network :hostonly, "33.33.33.10"

        # Provisioning by puppet
        amsterdamphp_config.vm.provision :puppet do |puppet|
            puppet.manifests_path = "support/puppet/manifests"
            puppet.module_path = "support/puppet/modules"
            puppet.manifest_file = "amsterdamphp.pp"
            puppet.options = [ '--verbose' ]
        end
    end
end
