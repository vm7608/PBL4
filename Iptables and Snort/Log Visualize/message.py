# thống kê các cảnh báo của snort, ở đây chỉ ví dụ với một file alert.csv
# nếu chạy trên hệ thống thực tế thì sẽ trỏ đến đường dẫn file alert.csv của hệ thống
from matplotlib.patches import Patch
import random
import matplotlib.pyplot as plt
import pandas as pd
import numpy as np
from matplotlib.patches import Rectangle
# Read the Snort log file into a Pandas dataframe
df = pd.read_csv('alert.csv', sep=',',
                 names=['timestamp', 'msg', 'proto', 'src_ip', 'src_port', 'dst_ip', 'dst_port'])
df = df.fillna(0)
df = df.astype(str)

msg_counts = df['msg'].value_counts()

# Calculate the percentage of each message
total = msg_counts.sum()
msg_percents = msg_counts / total * 100

# Get the labels and sizes for the bar chart
labels = msg_counts.index
sizes = msg_percents.values

# Generate random colors for the bar chart
colors = []
for i in range(len(sizes)):
    r = random.randint(0, 255)
    g = random.randint(0, 255)
    b = random.randint(0, 255)
    colors.append(f"#{r:02x}{g:02x}{b:02x}")

# Create a list of Rectangle objects with the colors and labels
artists = []
for label, color in zip(labels, colors):
    rect = Rectangle((0, 0), 1, 1, fc=color)
    artists.append(rect)

# Create the bar chart
plt.bar(labels, sizes, color=colors)

# Add a title and y-axis label
plt.title("Snort Messages")
plt.ylabel("Percentage")
plt.xticks(rotation=45)
# Add the percentage values above the bars
for i, v in enumerate(sizes):
    plt.text(i, v, f"{v:.5f}%", ha='center')

# Create a legend with the message of each color and place it at the top right corner of the plot
plt.legend(artists, labels, title="Messages",
           bbox_to_anchor=(0.6, 0.6), loc="upper left")

# Show the plot
plt.show()
