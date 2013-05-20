<?php

namespace Miami\Core;

class Route extends Singleton {
	
	/**
	 * Default route constant, basically a hack for a wildcard modifier.
	 */
	const RT_DEFAULT = ':*';
	
	
	/**
	 * Default options for route parsing engine.
	 *  
	 * @var array
	 */
	protected $options = array(
		'case_sensitive' => false,
		'regex' => true,
		'modifiers' => true,
		'user_modifiers' => true,
		'callback' => null
	);
	
	
	/**
	 * Contains all options for a route parsing instance.
	 * 
	 * @var array
	 */
	protected $temp_options = array();
	
	
	/**
	 * Temporarily stores named option groups.
	 * 
	 * @var array
	 */
	protected $current_options = array();
	
	
	/**
	 *
	 * @var type 
	 */
	protected $current_option_groups = array();
	
	
	/**
	 * Stores routes which are registered.
	 * 
	 * @var array
	 */
	protected $routes = array();
	
	
	/**
	 * Stores all predefined segment modifiers.
	 * 
	 * @var array
	 */
	protected $modifiers = array(
		
		
		// Matches any signed or unsigned integer
		':int' => '-?[0-9]{1,}',
		
		
		// Matches any numerical digit
		':digit' => '[0-9]{1,}',
		
		
		// Matches any signed or unsigned floating point number
		':float' => '-?[0-9]{1,}\.[0-9]{1,}',
		
		
		// Matches any digit
		':num' => '\d+',
		
		
		// Matches any hex number, also supports starting with 0x
		':hex' => '(?:0x)?[A-F0-9]+',
		
		
		// Matches anything within the section
		':any' => '[^/]+',
		
		
		// Matches everything from this point onwards
		':*' => '.*',
		
		
		// Matches any binary sequence
		':bin' => '[0-1]+'
	);
	
	
	/**
	 * Contains all user modifiers and their associated closure.
	 * 
	 * @var array
	 */
	protected $user_modifiers = array();
	
	
	/**
	 * Stores the current segment index of the route.
	 * 
	 * @var integer
	 */
	protected $route_index = 0;
	
	
	/**
	 * Stores exploded version of given URI.
	 * 
	 * @var array 
	 */
	protected $route_uri = array();
	
	
	/**
	 * Register a route which will be used to match against a given URI.
	 * 
	 * @param string $route
	 * @param mixed $options
	 */
	public function register($route, $options = null) {
		
		$this->routes[$route] = $options;
	}
	
	
	/**
	 * Register a user modifier which can be used as part of a route using
	 * the double colon identifier, ::$name.
	 * 
	 * @param string $name
	 * @param closure $callback
	 */
	public function register_modifier($name, $callback) {
		
		$this->user_modifiers[$name] = $callback;
	}
	
	
	/**
	 * Run the given URI against all of the available routes and try to find
	 * a match for the URI using the routes and their options. Upon success,
	 * fire a callback if specified. 
	 * 
	 * @param string $uri
	 * @param array | callable $routes
	 * @param callable $callback
	 */
	public function run($uri, $routes = array(), $callback = null) {
		
		
		// Clean the uri
		$uri = trim((string) $uri, '/');
	
		
		// Set the callback if no routes are set
		if (is_callable($routes)) {
			$callback = $routes;
		}
		
		
		// Select the routes
		$routes = !is_array($routes) || empty($routes) ? $this->routes : $routes;
		
		
		// Reset current variables
		$this->current_options = $this->options;		
		$this->current_option_groups = array();
		
		
		// Make an array out of the uri
		$this->route_uri = explode('/', $uri);
		
		
		// Loop through the routes and options
		foreach ($routes as $route => $options) {
			
			
			// Check if reset is called and reset options but only in option groups
			if (is_int($route) && is_array($options) && isset($options['reset']) && $options['reset']) {

				$this->current_options = $this->options;
			}
			
			
			// Reset temporary options
			$this->temp_options = $this->current_options;
			
			
			// If it is just a options setter
			if (is_int($route) && is_array($options)) {
				
				
				// Merge options
				$this->current_options = $options + $this->current_options;
				
				
				// Save options if named
				if (isset($options['name'])) {
					
					$this->current_option_groups[$options['name']] = $options;
				}
				
				continue;
			}
			
			
			// Check if route with options
			if (is_string($route) && is_array($options)) {
				
				if (isset($options['reset']) && $options['reset']) {
					
					$this->temp_options = $this->options;
				}
				
				if (isset($options['use']) && isset($this->current_option_groups[$options['use']])) {
					
					$this->temp_options = $this->current_option_groups[$options['use']] + $this->temp_options;
				}
				
				$this->temp_options = $options + $this->temp_options;
			}
			
			
			// Check if just route and callback
			if (is_string($route) && is_callable($options)) {
				
				$this->temp_options['callback'] = $options;
			}
			
			
			// Check if only route is passed
			if (is_int($route) && is_string($options)) {
				
				$route = $options;
			}
			
			
			// Set the temporary options with modifiers and user modifiers
			if ($this->temp_options['modifiers'] === true)
				$this->temp_options['modifiers'] = $this->modifiers;
			elseif (!$this->temp_options['modifiers'])
				$this->temp_options['modifiers'] = array();
			
			if ($this->temp_options['user_modifiers'] === true)
				$this->temp_options['user_modifiers'] = $this->user_modifiers;
			elseif (!$this->temp_options['user_modifiers'])
				$this->temp_options['user_modifiers'] = array();
	
			
			// Start the route index
			$this->route_index = 0;
			
			
			// See if route is default route
			if ($route == static::RT_DEFAULT) {
				
				$this->temp_options['modifiers'] = array(
					$route => $this->modifiers[$route]
				);
			}
			
			
			// Clean up the route if it contains wildcard modifier
			$route = preg_replace('@((^|/):\*(/|$)).*$@', '$1', $route);
			
			
			// Parse the route
			$route = preg_replace_callback('@(?<=/|)(\\\\)?([^/]+)@', 'static::parse_route', $route);
			
			
			// Clean up regex modifiers
			$route = preg_replace('@([^/]+)@', '($1)', $route);

			
			// Build route regex
			$route = '@^' . $route . '$@' . ($this->temp_options['case_sensitive'] ? '' : 'i');
			

			$segments = array();

			
			// Try to match the route with the uri
			if (preg_match($route, $uri, $segments)) {

				
				// Remove global group match
				array_shift($segments);
				
				
				// Set callback if in options
				if (is_callable($this->temp_options['callback'])) {
					
					$callback = $this->temp_options['callback'];
				}
				
				
				// If there is a callable callback, run it and pass segments as parameter
				if (is_callable($callback)) {
					
					$callback($segments);
				}
				
				break;
			}
		}
	}
	
	
	/**
	 * The URI segment parsing engine which gets called via the
	 * preg_replace_callback in the run() method.
	 * 
	 * @param array $matches
	 * @return string
	 */
	protected function parse_route($matches) {
		
		$this->route_index++;
		
		
		// Check if modifier exists and if it does, return the logic
		if (isset($this->temp_options['modifiers'][$matches[0]]) && $matches[1] == '') {
			
			return $this->temp_options['modifiers'][$matches[0]];
		}
		
		
		// Check if start of user modifier
		if (strpos($matches[0], '::') === 0) {
			
			$match = ltrim($matches[0], ':');
			
			
			// Check if user modifier exists and if route segment exists
			if (isset($this->temp_options['user_modifiers'][$match]) && isset($this->route_uri[$this->route_index - 1])) {
				
				$callback = $this->temp_options['user_modifiers'][$match];
				
				
				// Run callback and return segment pass or fail
				if ($callback($this->route_uri[$this->route_index - 1])) {
					
					return preg_quote($this->route_uri[$this->route_index - 1], '@');
				} else {
					
					return '_' . $matches[0];
				}
			}
		}
		
		
		// See if segment was not escaped
		$match = $matches[1] == '\\' ? $matches[2] : $matches[0];
		
		
		// Check if regex modifier and if regex is allowed
		if ($matches[1] == '' && isset($this->temp_options['regex']) && $this->temp_options['regex']) {
			
			
			// Format regex modifier for route
			return preg_replace('/^:@([^\/]*)@$/', '$1', $match);
		}
		
		
		// Sanitize what remains
		return preg_quote($match, '@');
	}
	
	
	/**
	 * Redirect to specified uri. Supports the following uri types:
	 * 
	 * protocol://uri
	 * //uri -> http://uri
	 * ./uri -> app_domain.com/uri
	 * /uri -> app_domain.com/application/uri
	 * uri -> app_domain.com/application/current/location/uri
	 * 
	 * @param string $uri
	 */
	public function redirect($uri) {
		
		$location = $uri;


		// Check if uri is not an external link
		if (!preg_match('@^\w+://[^$]*@', $uri)) {


			// Safely get offsets so no notice is thrown
			$uri_first = isset($uri[0]) ? $uri[0] : null;
			$uri_second = isset($uri[1]) ? $uri[1] : null;

			switch ($uri_first) {


				// Check if uri is absolute
				case '/':
					switch ($uri_second) {


						// uri maps to http
						case '/':
							$location = 'http:' . $uri;
							break;


						// uri maps to absolute of application
						default:
							$location = '/' . Cms::get_current_app() . $uri;
							break;
					}
					break;

				case '.':


					// uri maps to absolute of domain
					if ($uri_second == '/') {
						$location = substr($uri, 1);
						break;
					}
			}
		}

		header('location: ' . $location);
		die;
	}
}