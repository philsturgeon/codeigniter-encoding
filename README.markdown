CodeIgniter-Prowl
=================

Send iPhone notifications from your CodeIgniter application with the Prowl library.
Based on the PHP Prowl from Leon Bayliss and cleaned up.


Requirements
------------

1. PHP 5.1+
2. CodeIgniter 1.6.x - 2.0-dev
3. iPhone with [Prowl App](http://itunes.apple.com/app/prowl-growl-client/id320876271?mt=8)
3. [Prowl account](https://prowl.weks.net/register.php)


Usage
-----

	$config['username'] = 'KanyeWest';
	$config['password'] ='douch3b4g1977';
	
	// optional. Defaults to CI Prowl
	$config['application'] = "Kayne's Calender";
	
	$this->load->library('prowl', $config);
	
	$result = $this->prowl->send('Reminder', 'Be an idiot in public.');
	
	print_r($result);

Simple as that! 


To-do
-----

I'll add a config file for it at some point.


Extra
-----

If you'd like to request changes, report bug fixes, or contact
the developer of this library, email <email@philsturgeon.co.uk>