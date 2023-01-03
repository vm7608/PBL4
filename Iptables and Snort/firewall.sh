# Description: Firewall script for Linux
# Cách chạy: sudo ./firewall.sh
# reset toàn bộ rule về mặc định
iptables -t filter -F
iptables -t filter -X


# từ chối tất cả truy cập vào máy chủ
# đặt mặc địch các chain input, output, forward là drop, có nghĩa là từ chối tất cả, chỉ khi nào phù hợp 1 rule accept thì mới cho qua
iptables -t filter -P INPUT DROP
iptables -t filter -P FORWARD DROP
iptables -t filter -P OUTPUT DROP


# Sẵn sàng kết nối
# hai luật dưới cho phép các gói là một phần của một kết nối đã thiết lập được phép đi qua tường lửa
# The --state RELATED,ESTABLISHED option specifies that the rule should match packets that are part of a related connection or an established connection.
iptables -A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -m state --state RELATED,ESTABLISHED -j ACCEPT


# loop-back (localhost)
# cho phép các lưu lượng trao đổi bên trong hệ thống thông qua localhost
iptables -t filter -A INPUT -i lo -j ACCEPT
iptables -t filter -A OUTPUT -o lo -j ACCEPT


# Chặn ping vào hệ thống
iptables -t filter -A INPUT -p icmp -j REJECT
iptables -t filter -A OUTPUT -p icmp -j REJECT

# HTTP WEB
# cho phép truy cập bằng giao thức tcp vào cồng 80 (tức là giao thức HTTP)
iptables -t filter -A INPUT -p tcp --dport 80 -j ACCEPT
iptables -t filter -A OUTPUT -p tcp --dport 80 -j ACCEPT


# DNS
# cổng 53 được dùng bởi DNS (domain name system), phân giải tên miền thành địa chỉ IP, luật này cho phép truy vấn và phản hồi DNS đi qua tường lửa
# nếu bật luật này thì hệ thống lên internet được
iptables -t filter -A OUTPUT -p tcp --dport 53 -j ACCEPT
iptables -t filter -A OUTPUT -p udp --dport 53 -j ACCEPT
iptables -t filter -A INPUT -p tcp --dport 53 -j ACCEPT
iptables -t filter -A INPUT -p udp --dport 53 -j ACCEPT


# NTP
# cổng 123 được dùng bởi NTP (network time protocol), dùng để đồng bộ đồng hồ của các thiết bị trên mạng, đảm bảo đồng hồ trên hệ thống chính xác
iptables -t filter -A OUTPUT -p udp --dport 123 -j ACCEPT



# ANTI DDOS
# các lệnh sau đều là accept, tức là chúng ta đã đóng tất cả forward ở dòng 9, giờ chúng ta giới hạn các quy tắc mà nếu gói tin tuân thủ thì mới được đi qua
# rule dưới cho phép gói tin tcp, có cờ syn bật, được đi qua tường lửa với lưu lượng 1 gói/giây
iptables -A FORWARD -p tcp --syn -m limit --limit 1/second -j ACCEPT

# tương tự nhưng với gói udp
iptables -A FORWARD -p udp -m limit --limit 1/second -j ACCEPT

# cho phép ICMP echo request (ping) chuyển tiếp qua hệ thống, dùng để test kết nối, chuẩn đoán sự cố mạng
iptables -A FORWARD -p icmp --icmp-type echo-request -m limit --limit 1/second -j ACCEPT

# cho phép gói tin tcp có bật cờ RST chuyển tiếp với lưu lượng 1 gói/s; cờ RST dùng để đặt lại kết nối TCP và kết thúc kết nối.
iptables -A FORWARD -p tcp --tcp-flags SYN,ACK,FIN,RST RST -m limit --limit 1/s -j ACCEPT

# như trên nhưng với gói tin đi vào hệ thống (phía trên chỉ là chuyển tiếp, không đi vào hệ thống)
iptables -A INPUT -p tcp -m tcp --tcp-flags RST RST -m limit --limit 2/second --limit-burst 2 -j ACCEPT

#---------------------------------------------
# config bảng mangle 

# XÓA CÁC gói không hợp lệ  bằng cách theo dõi trạng trái,nếu không hợp lệ thì drop
iptables -t mangle -A PREROUTING -m conntrack --ctstate INVALID -j DROP

# Bỏ các gói TCP mới và không phải là SYN
iptables -t mangle -A PREROUTING -p tcp ! --syn -m conntrack --ctstate NEW -j DROP

# Bỏ các gói SYN có giá trị MSS đáng ngờ
#iptables -t mangle -A PREROUTING -p tcp -m conntrack --ctstate NEW -m tcpmss ! --mss 536:65535 -j DROP


# chặn các gói tin có cờ TCP giả
iptables -t mangle -A PREROUTING -p tcp --tcp-flags FIN,SYN,RST,PSH,ACK,URG NONE -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags FIN,SYN FIN,SYN -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags SYN,RST SYN,RST -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags FIN,RST FIN,RST -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags FIN,ACK FIN -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ACK,URG URG -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ACK,FIN FIN -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ACK,PSH PSH -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ALL ALL -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ALL NONE -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ALL FIN,PSH,URG -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ALL SYN,FIN,PSH,URG -j DROP 
iptables -t mangle -A PREROUTING -p tcp --tcp-flags ALL SYN,RST,ACK,FIN,URG -j DROP  


# chặn các gói tin giả
# chặn trong mangle tables (bảng này kiểm tra header của cách gói tin), chain prerouting (kiểm tra packet trước khi được chuyển đến địa chỉ cuối cùng) 
# phạm vị chặn bởi các quy tắc này bao gồm từ địa chỉ IP riêng, địa chỉ IP chưa được định tuyến trên internet
iptables -t mangle -A PREROUTING -s 224.0.0.0/3 -j DROP 
iptables -t mangle -A PREROUTING -s 169.254.0.0/16 -j DROP 
iptables -t mangle -A PREROUTING -s 172.16.0.0/12 -j DROP 
iptables -t mangle -A PREROUTING -s 10.0.0.0/8 -j DROP 
iptables -t mangle -A PREROUTING -s 0.0.0.0/8 -j DROP 
iptables -t mangle -A PREROUTING -s 240.0.0.0/5 -j DROP 
iptables -t mangle -A PREROUTING -s 127.0.0.0/8 ! -i lo -j DROP


# drop icmp
iptables -t mangle -A PREROUTING -p icmp -j DROP 

# drop những gói tin phân mảnh để tránh bị phát hiện, ta loại bỏ các gói tin có cờ phân mảnh
# rule này được thêm vào chain prerouting của bảng mangle ==> xử lý các gói được chuyển tiếp đến local host
iptables -t mangle -A PREROUTING -f -j DROP

#kết thúc config bảng mangle
#---------------------------------------------


# chặn các gói tin giả với chain INPUT
iptables -A INPUT -s 10.0.0.0/8 -j DROP
iptables -A INPUT -s 169.254.0.0/16 -j DROP
iptables -A INPUT -s 172.16.0.0/12 -j DROP
iptables -A INPUT -s 127.0.0.0/8 -j DROP
iptables -A INPUT -s 224.0.0.0/4 -j DROP
iptables -A INPUT -d 224.0.0.0/4 -j DROP
iptables -A INPUT -s 240.0.0.0/5 -j DROP
iptables -A INPUT -d 240.0.0.0/5 -j DROP
iptables -A INPUT -s 0.0.0.0/8 -j DROP
iptables -A INPUT -d 0.0.0.0/8 -j DROP
iptables -A INPUT -d 239.255.255.0/24 -j DROP
iptables -A INPUT -d 255.255.255.255 -j DROP



# Drop all invalid packets
iptables -A INPUT -m state --state INVALID -j DROP
iptables -A FORWARD -m state --state INVALID -j DROP
iptables -A OUTPUT -m state --state INVALID -j DROP


# Accept những gói tin thỏa mãn 
# limit chỉ định số lượng tối đa gói tin đi qua tường trong 1 giây
# limit-burst chỉ định có tối đa 30 gói tin được đi qua trong 1 đợt
iptables -A INPUT -p tcp --syn -m limit --limit 2/s --limit-burst 30 -j ACCEPT
iptables -A INPUT -p icmp --icmp-type echo-request -m limit --limit 1/s -j ACCEPT
iptables -A INPUT -p tcp --tcp-flags ALL NONE -m limit --limit 1/h -j ACCEPT
iptables -A INPUT -p tcp --tcp-flags ALL ALL -m limit --limit 1/h -j ACCEPT


# Accept các gói tin đến cổng 3306 - MYSQL
iptables -t filter -A INPUT -p tcp --dport 3306 -j ACCEPT
iptables -t filter -A INPUT -p udp --dport 3306 -j ACCEPT

# Accept đến cổng TS APPS
iptables -t filter -A OUTPUT -p tcp --dport 41144 -j ACCEPT
iptables -t filter -A INPUT -p tcp --dport 41144 -j ACCEPT


# QUERY
iptables -t filter -A OUTPUT -p tcp --dport 10011 -j ACCEPT
iptables -t filter -A INPUT -p tcp --dport 10011 -j ACCEPT
iptables -t filter -A OUTPUT -p tcp --dport 30033 -j ACCEPT
iptables -t filter -A INPUT -p tcp --dport 30033 -j ACCEPT


# Port 587 dùng để gửi email thông qua SMTP
iptables -t filter -A OUTPUT -p udp --dport 587 -j ACCEPT
iptables -t filter -A INPUT -p udp --dport 587 -j ACCEPT
iptables -t filter -A OUTPUT -p udp --dport 587 -j ACCEPT
iptables -t filter -A INPUT -p udp --dport 587 -j ACCEPT


# ANTI DDOS Production Server WEB
# tạo 1 chain mới
iptables -N http-flood
# gói tin đang ở trong chain input, nếu thỏa điều kiện sau thì nhảy vào chain http-flood
# điều kiện: có 1 gói tin TCP, bật cờ syn, tới port 80
# connlimit-above xác định số lượng kết nối tối đa sẽ được phép, đối với lưu lượng phù hợp rule
iptables -A INPUT -p tcp --syn --dport 80 -m connlimit --connlimit-above 1 -j http-flood
iptables -A INPUT -p tcp --syn --dport 443 -m connlimit --connlimit-above 1 -j http-flood

# nếu mà kiểm tra số lượng gói tin đi qua tường lửa không quá 10 pac/s và số lượng đi qua 1 đợt ko quá 10, thì trả gói về chuỗi gọi chain http-flood, còn nó mà lớn hơn 10 pac/s và 10 gói/ đợt thì xuống dưới drop
iptables -A http-flood -m limit --limit 10/s --limit-burst 10 -j RETURN
iptables -A http-flood -m limit --limit 1/s --limit-burst 10 -j LOG --log-prefix "HTTP-FLOOD"
iptables -A http-flood -j DROP


# nếu tạo quá 20 kết nối sẽ drop
iptables -A INPUT -p tcp --syn --dport 80 -m connlimit --connlimit-above 20 -j DROP
iptables -A INPUT -p tcp --syn --dport 443 -m connlimit --connlimit-above 20 -j DROP

# nếu các gói là một phần của kết nối mới
iptables -A INPUT -p tcp --dport 80 -i ens33 -m state --state NEW -m recent --set
# recent là kiểm tra lịch sử kết nối gần đây, update để cập nhật lịch sử kết nối cho gói, khoảng thời gian kiểm tra lịch sử kết nối là 10s, nếu nó có 20 kết nối gần đây thì drop
iptables -I INPUT -p tcp --dport 80 -m state --state NEW -m recent --update --seconds 10 --hitcount 20 -j DROP

#như trên nhưng cho https
iptables -A INPUT -p tcp --dport 443 -i eth0 -m state --state NEW -m recent --set
iptables -I INPUT -p tcp --dport 443 -m state --state NEW -m recent --update --seconds 10 --hitcount 20 -j DROP
iptables -A INPUT -p tcp --syn -m limit --limit 10/s --limit-burst 13 -j DROP


iptables -t filter -N syn-flood
iptables -t filter -A INPUT -i ens33 -p tcp --syn -j syn-flood

# nếu đảm bảo max 1 pac/s và max 4 pac/đợt thì trả về chuỗi cũ
iptables -t filter -A syn-flood -m limit --limit 1/sec --limit-burst 4 -j RETURN
iptables -t filter -A syn-flood -j LOG --log-prefix "IPTABLES SYN-FLOOD:"
iptables -t filter -A syn-flood -j DROP




iptables -A INPUT -p tcp -m connlimit --connlimit-above 80 -j REJECT --reject-with tcp-reset
iptables -A INPUT -p tcp -m conntrack --ctstate NEW -m limit --limit 60/s --limit-burst 20 -j ACCEPT
iptables -A INPUT -p tcp -m conntrack --ctstate NEW -j DROP



# giới hạn số kết nối với 1 địa chỉ IP/ reject và gửi thông báo lỗi
iptables -A INPUT -p tcp -m connlimit --connlimit-above 111 -j REJECT --reject-with tcp-reset
# giới hạn số kết nối tcp/s từ 1 địa chỉ IP
iptables -A INPUT -p tcp -m conntrack --ctstate NEW -m limit --limit 60/s --limit-burst 20 -j ACCEPT 
iptables -A INPUT -p tcp -m conntrack --ctstate NEW -j DROP  


# Ngăn việc reset flags
iptables -A INPUT -p tcp --tcp-flags RST RST -m limit --limit 2/s --limit-burst 2 -j ACCEPT 
iptables -A INPUT -p tcp --tcp-flags RST RST -j DROP


# kiểm tra nguy cơ quét port
iptables -N port-scanning
iptables -A port-scanning -p tcp --tcp-flags SYN,ACK,FIN,RST RST -m limit --limit 1/s --limit-burst 2 -j RETURN
iptables -A port-scanning -j DROP