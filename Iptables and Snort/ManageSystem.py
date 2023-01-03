import tkinter as tk
from tkinter import messagebox
import pandas as pd
import subprocess
from subprocess import call
# Create the main window
root = tk.Tk()
root.title("Snort Alerts")

# Danh sách những ip được cho phép
white_list = ['192.168.100.119', '192.168.100.32']
# Mật khẩu của máy
pwd = '20020808'


def drop_packets():
    # Get the IP address from the input field
    ip_address = input_field.get()
    if (ip_address == ""):
        return

    cmd1 = 'iptables -t mangle -A PREROUTING -s ' + ip_address + ' -j DROP'
    cmd2 = 'iptables -I INPUT -s ' + ip_address + ' -j DROP'

    call('echo {} | sudo -S {}'.format(pwd, cmd1), shell=True)
    call('echo {} | sudo -S {}'.format(pwd, cmd2), shell=True)
    messagebox.showinfo("Information", ip_address + " is banned!")


def auto_drop():
    for df in pd.read_csv("/var/log/snort/alert.csv", sep=',',
                          names=['timestamp', 'msg', 'proto', 'src_ip', 'src_port', 'dst_ip', 'dst_port'], chunksize=100000):
        # Get the last 10k rows of the chunk
        last = df.tail(10000)

    last = last.fillna(0)
    last = last.astype(str)
    data = last.values
    # Create an empty list to store the src_ip addresses
    src_ip_list = []
    # Loop through the data
    for row in data:
        # Get the src_ip address
        if (row[1] in ['DOS Teardrop attack', 'UDP Flood Attack', 'SYN Flood DDoS Attack', 'BAD-TRAFFIC tcp port 0 traffic', 'Flood DDoS Attack']):
            src_ip_list.append(row[3])
    # Print the list of src_ip addresses
    src_ip_set = set(src_ip_list)
    # Convert the set back to a list
    src_ip_list = list(src_ip_set)
    # remove white list ip
    for wip in white_list:
        if wip in src_ip_list:
            src_ip_list.remove(wip)
    if (len(src_ip_list) != 0):
        if (len(src_ip_list) > 1000):
            # chống tấn công syn dùng random ip, chỉ cho phép white list được dùng hệ thống
            syn1 = 'iptables -t raw -A PREROUTING -p tcp -m tcp --syn -j CT --notrack'
            call('echo {} | sudo -S {}'.format(pwd, syn1), shell=True)
            for wip in white_list:
                allow1 = 'iptables -t raw -I PREROUTING -s ' + wip + ' -j RETURN'
                allow2 = 'iptables -t raw -I OUTPUT -d ' + wip + ' -j ACCEPT'
                call('echo {} | sudo -S {}'.format(pwd, allow1), shell=True)
                call('echo {} | sudo -S {}'.format(pwd, allow2), shell=True)
            messagebox.showinfo(
                "Random source DDoS attack detected! Only whitelisted is accepted!")

        else:
            # Chặn các ip nguy hiểm có trong cảnh báo của snort
            for ip in src_ip_list:
                cmd1 = 'iptables -t mangle -A PREROUTING -s ' + ip + ' -j DROP'
                cmd2 = 'iptables -I INPUT -s ' + ip + ' -j DROP'
                call('echo {} | sudo -S {}'.format(pwd, cmd1), shell=True)
                call('echo {} | sudo -S {}'.format(pwd, cmd2), shell=True)
            messagebox.showinfo("Information", str(
                len(src_ip_list)) + " IP is banned!")


# ---------------GUI--------------------
# Create a label and an input field for the IP address
label1 = tk.Label(root, text="Enter IP address:")
input_field = tk.Entry(root, width=60)
# Create the button
button1 = tk.Button(root, text="Drop Packets", command=drop_packets)
button2 = tk.Button(root, text="Auto drop", command=auto_drop)
# # Place the label and input field on the window
label1.grid(row=0, column=0, padx=10, pady=10)
input_field.grid(row=0, column=1, padx=10, pady=10)
button1.grid(row=0, column=2, padx=10, pady=10)
button2.grid(row=1, column=0, columnspan=3, padx=10, pady=10)
# Create the table
table = tk.LabelFrame(root, text="Table", padx=5, pady=5)
table.grid(row=2, column=0, columnspan=3, padx=10, pady=10)
# ---------------GUI--------------------


def refresh_table():

    for df in pd.read_csv("/var/log/snort/alert.csv", sep=',',
                          names=['timestamp', 'msg', 'proto', 'src_ip', 'src_port', 'dst_ip', 'dst_port'], chunksize=100000):

        last = df.tail(20)
    last = last.fillna(0)
    last = last.astype(str)
    labels = last.columns
    data = last.values

    # Clear the table
    for widget in table.winfo_children():
        widget.destroy()

    # Add the labels to the table
    for i in range(len(labels)):
        tk.Label(table, text=labels[i]).grid(row=0, column=i)

    # Add the data to the table
    for i in range(len(data)):
        for j in range(len(labels)):
            tk.Label(table, text=data[i][j]).grid(row=i+1, column=j)


# Create the refresh button
button = tk.Button(root, text="Refresh", command=refresh_table)
button.grid(row=3, column=0, columnspan=3, padx=10, pady=10)

# Initialize the table with the first set of data
refresh_table()

# Run the main loop
root.mainloop()
