# chạy server trên lampp
# cách dùng: sudo ./runServer.sh
# nếu máy đã cài apache thì cần dừng apache lại và chạy apache của lampp
# nếu máy chưa cài apache thì có thể xóa câu lệnh dòng thứ 5

sudo service apache2 stop
sudo /opt/lampp/lampp start

chmod +x firewall.sh
sudo ./firewall.sh
echo "Firewall is on!"
