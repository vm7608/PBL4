# chạy snort để cảnh báo
# các dùng: sudo ./snort.sh
#print alert in to console
sudo snort -q -i ens33 -A console -c /etc/snort/snort.conf
#not print alert in console but save log to csv file
#sudo snort -q -i ens33 -c /etc/snort/snort.conf
