<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

class CustomizeFormatter
{
	/**
	 * Customize the given logger instance.
	 *
	 * @param  \Illuminate\Log\Logger  $logger
	 * @return void
	 */
	public function __invoke($logger)
	{
		$LONG_FORMATTER = "[%datetime%] [%channel%.%level_name%|%extra.class%@%extra.function%:%extra.line%]: %message% %context%\n";

		foreach ($logger->getHandlers() as $handler) {
			$Formatter = new LineFormatter($LONG_FORMATTER, 'Y-m-d H:i:s', false, true);
			$Formatter->includeStacktraces();
			// processor, adding URI, IP address etc. to the log
			// $handler->pushProcessor(new WebProcessor());
			// processor, memory usage
			// $handler->pushProcessor(new MemoryUsageProcessor());
			// processor, to have file, function, class, line
			$handler->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, array(), 4));
			$handler->setFormatter($Formatter);

			/**
			 * Incase need to log User ID - can refer below
			 *
			 * http://zetcode.com/php/monolog/
			 * https://laravel-tricks.com/tricks/monolog-for-custom-logging
			 */
		}
	}
}