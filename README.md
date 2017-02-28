# cf-prep-tool
A set of PHP scripts to help probe the networking environment prior to installing Pivotal Cloud Foundry.

# Assumptions
These scripts assume the following:

1. That PCF will be deployed to a network behind at least one level of load-balancer.
The configuration file allows for two levels ("global" and "local") which would typically be the case where two foundations are to be installed, one each in separate data centers, with a global load-balancer balancing traffic across the DCs, and a local load-balancer balancing traffic across the GoRouters.

2. That SSL is terminated on both the global and local load-balancers.

3. That VMs on the network assigned to the Elastic Runtime have a route to the load-balancers. If the latter is not the case, then the test.php script can be run on another machine which does have a route.



# Installation

1. Create a Linux VM running on the network to which the ElasticRuntime will be deployed.

2. Assign the IP addresses which will be used by the GoRouters to the VM's network interface.
~~~~
ip add 192.168.1.254/24 dev eth1
ip add 192.168.1.253/24 dev eth1
ip add 192.168.1.252/24 dev eth1
~~~~

3. Install required packages
~~~~
yum install httpd2
yum install mod_rewrite
yum install php
yum install php_curl
yum install git
~~~~

4. Clone or otherwise copy this repository into the DocumentRoot of the default apache install
~~~~
cd /var/www/html
git clone https://github.com/dmcintyre-pivotal/cf-prep-tool.git
~~~~

5. Edit the apache configuration file to match your system
~~~~
cd cf-prep-tool
vi conf/vhost.conf
~~~~
Adjust the document root

6. Install the apache configuration file (adjust paths for your system)
~~~~
cp conf/vhost.conf /etc/httpd2/sites-enabled/000-default.conf
~~~~

7. Restart apache
~~~~
service httpd2 restart
~~~~

8. Edit the tool settings to match your PCF settings
~~~~
vi conf/settings.ini
~~~~
There are comments for each setting

# Usage
There are three modes of usage, app emulation accessed by a browser, command-line, and GoRouter health emulation.

1. App emulation
The apache web-server is configured to forward all requests to the index.php script.
This script check first that the configuration in the settings.ini file is sane, then checks the http request being served:

* Is the name of the domain in one of the list of wildcard domains? This verifies that a forged request has not been forwarded by the load-balancers.

* Are the load-balancers setting headers needed by the GoRouters - X-FORWARDED-FOR and X-FORWARDED-PROTO? These tests can be disabled using the enableForwardedFor and enableForwardedPort entries in the settings.ini file.

2. Command-line
The test.php script automatically runs a series of tests sendings requests to the test VM using all registered DNS wildcards (or at least, all those you have specified in settings.ini), and using both HTTP and HTTPS.

Note that these tests will only work if the ERT network has a route (and DNS) to the load-balancer.

An example run looks like this:
~~~~
root@mac-vm-k16-04:/var/www/html# php test.php
Test Results
================
================================================================================================
settings.ini
================================================================================================
Check                            | Result
------------------------------------------------------------------------------------------------
Global Apps Domain               | OK: Set to apps.xyz.com
Global System Domain             | OK: Set to sys.xyz.com
Local Apps Domain                | OK: Set to apps.xyz-local.com
Local System Domain              | OK: Set to sys.xyz-local.com
Router IPs                       | OK:  Set to 127.0.0.1,127.0.0.2
------------------------------------------------------------------------------------------------

================================================================================================
Routing tests
================================================================================================
Check                            | Result
------------------------------------------------------------------------------------------------
HTTP Global Apps Domain test     | OK:  HTTP CODE 200 : http://toto.apps.xyz.com/json
                                 | OK Source IP Request on 127.0.0.1
                                 | OK Request host Host toto.apps.xyz.com in domain Global Apps Domain
                                 |
HTTP Global System Domain test   | OK:  HTTP CODE 200 : http://toto.sys.xyz.com/json
                                 | OK Source IP Request on 127.0.0.1
                                 | OK Request host Host toto.sys.xyz.com in domain Global System Domain
                                 |
HTTP Local Apps Domain test      | OK:  HTTP CODE 200 : http://toto.apps.xyz-local.com/json
                                 | OK Source IP Request on 127.0.0.1
                                 | OK Request host Host toto.apps.xyz-local.com in domain Local Apps Domain
                                 |
HTTP Local System Domain test    | OK:  HTTP CODE 200 : http://toto.sys.xyz-local.com/json
                                 | OK Source IP Request on 127.0.0.1
                                 | OK Request host Host toto.sys.xyz-local.com in domain Local System Domain
                                 |
HTTPS Global Apps Domain test    | FAILED: Error for https://tata.apps.xyz.com/json : 7 : Failed to connect to tata.apps.xyz.com port 443: Connection refused
HTTPS Global System Domain test  | FAILED: Error for https://tata.sys.xyz.com/json : 7 : Failed to connect to tata.sys.xyz.com port 443: Connection refused
HTTPS Local Apps Domain test     | FAILED: Error for https://tata.apps.xyz-local.com/json : 7 : Failed to connect to tata.apps.xyz-local.com port 443: Connection refused
HTTPS Local System Domain test   | FAILED: Error for https://tata.sys.xyz-local.com/json : 7 : Failed to connect to tata.sys.xyz-local.com port 443: Connection refused
------------------------------------------------------------------------------------------------

*** Some tests failed. Check the log **
~~~~

3. GoRouter emulation
Requests to the health endpoint on the configured port will return a 200 OK or any other status by configuring the unhealthyStatus and healthyHosts values in the settings.ini file.

You should modify these settings to simulate failure of each GoRouter in turn, checking that any monitoring or alerting from the load-balancer reacts as expected to the simulated failure.

You should also disable (pause) the VM entirely to check the load-balancer's reaction to total loss of connectivity.
