<?php

namespace hwao\WargamingClanBot;

class AccessTokenStore
{
	public function __construct(
		private string $path
	)
	{
	}

	public function get(): string
	{
		return (string)include $this->path;
	}

	public function set(string $accessToken)
	{
		\file_put_contents($this->path, '<?php return ' . \var_export($accessToken, true) . ';');
	}
}
