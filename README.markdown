CodeIgniter-Encoding
====================

Encode audio and video using the encoding.com API and service. Supports
Windows Media formats, QuickTime, Ogg, all sorts of stuff!

	http://www.encoding.com/wdocs/ApiDoc


Requirements
------------

1. PHP 5.1+
2. CodeIgniter 1.6.x - 2.0-dev
3. PHP 5 (configured with cURL enabled)
4. libcurl


Usage
-----

	$config['id'] = 'user_id';
	$config['key'] ='user_key';
	
	$this->load->library('encoding', $config);

	$upload_file = 'ftp://ftp_user:ftp_pass@example.com/httpdocs/uploads/somefile.mp4';

	$result = $this->encoding->encode($upload_file, array(
		'output' 				=> 'fl9',
		'size' 					=> '640x360',
		'bitrate' 				=> '600k',
		'audio_bitrate' 		=> '64k',
		'audio_sample_rate' 	=> 44100,
		'audio_channels_number' =>  2,
	);

	// Notify the correct domain when encoding is complete
	$this->encoding->notify('controller_or_url');

	// Displaying error if any
	if (!$result || $this->encoding->error_string())
	{
		show_error($this->encoding->error_string()
	}

	else
	{
		exit('And now we play the waiting game!');
	}