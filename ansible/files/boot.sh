#! /bin/sh

mkdir -p {{ write_to_folder }}
setfacl -R -m u:www-data:rwX -m u:vagrant:rwX {{ write_to_folder}}
setfacl -dR -m u:www-data:rwX -m u:vagrant:rwX {{ write_to_folder}}
mkdir -p {{ write_to_folder }}/logs

exit 0
