Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/trusty64"
    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "forwarded_port", guest: 3306, host: 3307

    config.vm.synced_folder ".", "/vagrant", disabled: true
    config.vm.synced_folder ".", "/opt/frameworkless"

    config.ssh.insert_key = true

    config.vm.provision "shell" do |s|
        # Do not use this password in production, please.
        s.args = ["local-development"]
        s.path = "./setup/install/initial_trusty64.sh"
    end
end
