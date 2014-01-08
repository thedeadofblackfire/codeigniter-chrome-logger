<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Copyright 2013-2014 Gustavo Rubio - gustavo@42ideas.mx
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/** 
 * Basic usage:
 *
 * Loading it from your controller:
 * 
 * $this->load->library('ChromeLogger');
 *
 * Basic usage: 
 *
 * [SEND INFO, $var can be anything ]:
 * $this->chromelogger->info($var = 'This is an INFO message');
 * 
 * [SEND WARNING, $var can be anything ]:
 * $this->chromelogger->warn($var = 'This is a WARN message'); 
 *
 * [SEND ERROR, $var can be anything ]:
 * $this->chromelogger->error($var = 'This is an ERROR message');
 *
 * [JUST LOG, $var can be anything ]:
 * $this->chromelogger->log($var = 'This is just a LOG');
 *
 * [GROUP, Send information grouped, $var is the header for that group ]:
 * $this->chromelogger->group($var = 'Group I');
 * $this->chromelogger->info('Some info for group I');
 * $this->chromelogger->info('Some more info for group I');
 * $this->chromelogger->group_end();  
 * 
 * $this->chromelogger->group($var = 'Group II');
 * $this->chromelogger->info('Some info for group II');
 * $this->chromelogger->info('Some more info for group II');
 * $this->chromelogger->group_end();
 *
 * [GROUP COLLAPSED, Same as group() only it will send it so that it is collapsed on console, $var is the header for that group ]:
 * $this->chromelogger->group_collapsed($var = 'Group II');
 * $this->chromelogger->info('Some info for group II');
 * $this->chromelogger->info('Some more info for group II');
 * $this->chromelogger->group_end();
 * 
 * [TABLE, will display the info as an ordered table on console, $var can be anything but preferably a list of objects so it actually makes sense ]:
 * $p1 = new Person('John', 'Wayne', 'M');
 * $p2 = new Person('Gena', 'Rowllands', 'F');
 * $p3 = new Person('Clark', 'Gable', 'M');
 *
 * $this->chromelogger->table($p1, $p2, $p3);
 */

require_once('ChromePhp/ChromePhp.php');

class Chrome_logger {
	
	private $chromephp = NULL;
	private $log_allowed = FALSE;
	private $log_refusal_msg = '';
	private $log_refusal_sent = FALSE;

	public function __construct() 
	{
		//Save this as reference so we have it availlable through all our instance
		$this->chromephp =& ChromePhp::getInstance();

		if(defined('ENVIRONMENT')) 
		{
			switch (ENVIRONMENT)
			{
				case 'development':
					$this->log_allowed = TRUE;
				break;
			
				case 'testing':
				case 'production':
					$this->log_allowed = FALSE;
					$this->log_refusal_msg = "'ENVIRONMENT' constant not set to 'development' therefore, for security reasons, backtrace and logs from server are not shown. Set 'ENVIRONMENT' constant to 'development' to show such info here.";
					$log_refusal_sent = TRUE;
				break;

				default:
					$this->log_allowed = FALSE;
					$this->log_refusal_msg = "The application 'ENVIRONMENT' constant is set incorrectly. You must set it to 'development' in order to show server backtraces and logs here.";
					$log_refusal_sent = TRUE;
			}
		}
		else 
		{
			$this->log_allowed = FALSE;
			$this->log_refusal_msg = 'Application environment constant not set, so you cannot use this library!';
		}

		if($this->log_allowed == TRUE) 
		{
			//Change backtrace level to 2, otherwise we are going to get all backtrace info sent 
			//from the main ChromePhp.php file and ChromePhp class since we are wrapping it here
			$this->chromephp->addSetting( ChromePHP::BACKTRACE_LEVEL, '2' );
		}
		else 
		{
			//dont show the actual file that initially generated the backtrace log refusal for security reasons
			$this->chromephp->addSetting( ChromePHP::BACKTRACE_LEVEL, '-1' );	
		}
	}

	private function is_logging_output_allowed() 
	{
		if($this->log_allowed == FALSE) 
		{
			//send header for refusal reason only once, at least for the current instance
			if($this->log_refusal_sent == FALSE) 
			{
				$this->chromephp->log($this->log_refusal_msg);
				$this->log_refusal_sent = TRUE;
			}

			return FALSE;
		}

		return TRUE;
	}

	public function log() 
	{
		if($this->is_logging_output_allowed()) 
		{
			return $this->chromephp->log( func_get_args() );	
		}
	}

	public function warn() 
	{
		if($this->is_logging_output_allowed()) 
		{
			return $this->chromephp->warn( func_get_args() );
		}
	}

	public function error() 
	{
		if($this->is_logging_output_allowed()) 
		{
			return $this->chromephp->error( func_get_args() );
		}
	}

	public function group() 
	{
		if($this->is_logging_output_allowed()) 
		{		
			return $this->chromephp->group( func_get_args() );
		}
	}

	public function info() 
	{
		if($this->is_logging_output_allowed()) 
		{
			return $this->chromephp->info( func_get_args() );
		}
	}

	public function group_collapsed() 
	{
		if($this->is_logging_output_allowed()) 
		{
			return $this->chromephp->groupCollapsed( func_get_args() );
		}	
	}

	public function group_end() 
	{
		if($this->is_logging_output_allowed()) 
		{
			return $this->chromephp->groupEnd( func_get_args() );	
		}
	}

	public function table() 
	{
		if($this->is_logging_output_allowed()) 
		{
			return $this->chromephp->table( func_get_args() );	
		}
	}

	public function add_setting($key, $value) 
	{
		if($this->is_logging_output_allowed()) 
		{		
			$this->chromephp->addSetting($key, $value);
		}
	}

	public function add_settings($settings) 
	{
		if($this->is_logging_output_allowed()) 
		{		
			$this->chromephp->addSettings($settings);
		}
	}

	public function get_setting($key) 
	{
		if($this->is_logging_output_allowed()) 
		{		
			return $this->chromephp->getSetting($key);
		}

		return NULL;
	}
}