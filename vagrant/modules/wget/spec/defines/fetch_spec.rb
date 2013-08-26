require 'spec_helper'

describe 'wget::fetch' do
  let(:title) { 'test' }

  default_params = {
    :source      => 'http://localhost/source',
    :destination => '/tmp/dest',
  }

  context "with default params" do
    let(:params) { default_params }

    it { should contain_exec('wget-test').with_command('wget --no-verbose --output-document=/tmp/dest http://localhost/source') }
  end

  context "with user" do
    let(:params) { default_params.merge({
      :execuser => 'testuser',
    })}

    it { should contain_exec('wget-test').with({
      'command' => 'wget --no-verbose --output-document=/tmp/dest http://localhost/source',
      'user'    => 'testuser'
    }) }
  end
end
