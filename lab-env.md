# How to read this document? 
This doc is written in markdown. This is the formatting language used on GitHub and various other documentation suites. It's a quick way

To edit and or view markdown (.md) docs in Windows, check out one of the following
* https://typora.io/#windows - Typora - Simple to download and setup
* https://code.visualstudio.com/ - Visual Studio Code + 'Markdown All In One' extension. Add the extension inside UI after installation (Ctrl+Shift+X). 
 
# Prep CentOS 8 

**Install Centos 8 'Minimal Install'**. This document doesn't cover this step. You can download CentOS here and install it on hardware or a virtual machine: 
https://www.centos.org/download/

After installation, apply the following modifications to the Operating system. 
```
# Add some packages
dnf -y install wget nano NetworkManager-tui 

# Disable SELinux. SELinux doesn't support docker at this time. 
setenforce 0
sed -i --follow-symlinks 's/SELINUX=enforcing/SELINUX=disabled/g' /etc/sysconfig/selinux

# Add firewall exceptions to kernel
modprobe br_netfilter
echo "net.bridge.bridge-nf-call-ip6tables = 1" >> /etc/sysctl.d/k8s.conf
echo "net.bridge.bridge-nf-call-iptables = 1" >> /etc/sysctl.d/k8s.conf
echo "net.ipv4.ip_forward = 1" >>  /etc/sysctl.d/k8s.conf
sysctl -p /etc/sysctl.d/*.conf

swapoff -a
# Modify /etc/fstab to not include swap. 
nano /etc/fstab

# Masquerade
firewall-cmd --add-masquerade --permanent
# Docker network 
firewall-cmd --zone=public --permanent --add-rich-rule 'rule family=ipv4 source address=172.17.0.0/16 accept'
# Reload firewall config to apply
firewall-cmd --reload
```

# Install Docker

Install Docker and Containerd. Check for updates to this cmd here: https://download.docker.com/linux/centos/7/x86_64/stable/Packages/ 

```
dnf config-manager --add-repo=https://download.docker.com/linux/centos/docker-ce.repo
dnf install -y  https://download.docker.com/linux/centos/7/x86_64/stable/Packages/containerd.io-1.2.6-3.3.el7.x86_64.rpm

dnf install -y docker-ce
systemctl enable docker
systemctl start docker
```

# Setup Kubernetes using Kind

Now, we need to install kind. Kind can build a quick kubernetes cluster inside of docker. It's container-ception. Hence the logo with kubernetes in a bottle.
https://kind.sigs.k8s.io/docs/user/quick-start/ 
```
# Download the file and set exectute permissions:
curl -Lo ./kind https://kind.sigs.k8s.io/dl/v0.8.0/kind-$(uname)-amd64
chmod +x ./kind

# Copy the downloaded file to a directory in $PATH so that it can be exected:
mv ./kind /usr/local/bin

# Create the cluster:
kind create cluster --name k8s-kind
```

# Install kubectl to remotely manage Kubernetes

We will need kubectl to manage remote and internal clusters. 
```
# Add the kubernetes package repository:
cat <<EOF > /etc/yum.repos.d/kubernetes.repo
[kubernetes]
name=Kubernetes
baseurl=https://packages.cloud.google.com/yum/repos/kubernetes-el7-x86_64
enabled=1
gpgcheck=1
repo_gpgcheck=1
gpgkey=https://packages.cloud.google.com/yum/doc/yum-key.gpg https://packages.cloud.google.com/yum/doc/rpm-package-key.gpg
exclude=kubelet kubeadm kubectl
EOF

# Install kubectl. The disable flag keeps the installation from updating every time yum update is run. 
yum -y install kubectl --disableexcludes=kubernetes
```

Now, let's verify that the cluster is working. You may or may not need the "--context" attribute. 
```
kubectl cluster-info --context kind-k8s-kind
kubectl cluster-info 
kubectl get nodes
kubectl get componentstatuses
```