# PBL4 - ITF - DUT - 2022

This is our PBL4 project about building an online shop website on Linux, configuring to prevent common cyber attacks like SQL Injection, XSS, Path traversal, DDoS.

The website may be done with all essential features :33.

## Prevent attacks?

About the attacks at the application layer like SQL Injection, XSS, or Path traversal, we mostly validate the input parameters and use PDO in the query to block.

Attack on the network layers like DDoS, we create some iptables rules to check and limit the rate of input packets to the server. Besides that, we use Snort to alert the attack. Also, we analyze the Snort log by Python in order to block traffic from dangerous IP addresses.

You can see the details in Docs folder.

## Contributors

- Cao Kieu Van Manh
- Luong Thien
- Nguyen Quoc Cuong
