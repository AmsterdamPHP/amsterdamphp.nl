set :application, "amsterdamphp"
set :domain,      "#{application}.nl"
set :deploy_to,   "/data/www/#{domain}"
set :app_path,    "app"

set :repository,  "git://github.com/AmsterdamPHP/amsterdamphp.nl.git"
set :scm,         :git

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]
set :use_composer, true

# Be more verbose by uncommenting the following line
 logger.level = Logger::MAX_LEVEL

default_run_options[:pty] = true
set :use_sudo, false

# Deploy from tags
namespace :deploy do

  desc "Deploy a specific tag"
  task :tag do
    transaction do
      set :branch do
        default_tag = `git tag`.split("\n").last

        tag = Capistrano::CLI.ui.ask "Tag to deploy (make sure to push the tag first): [#{default_tag}] "
        tag = default_tag if tag.empty?
        tag
      end unless exists?(:branch)

      find_and_execute_task("deploy")
    end
  end
end
