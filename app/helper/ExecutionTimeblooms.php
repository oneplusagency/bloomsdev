<?php

class ExecutionTimeblooms
{
    /**
     * @var mixed
     */
    private $startTime;
    /**
     * @var mixed
     */
    private $endTime;
    /**
     * @var int
     */
    private $compTime = 0;
    /**
     * @var int
     */
    private $systemTime = 0;

    /**
     * @var mixed
     */
    private $go;

    public function go()
    {
        $this->go = microtime( true );
    }

    public function segs()
    {
        return microtime( true ) - $this->go;
    }

    public function time()
    {
        $segs = $this->segs();
        $days = floor( $segs / 86400 );
        $segs -= $days * 86400;
        $hours = floor( $segs / 3600 );
        $segs -= $hours * 3600;
        $mins = floor( $segs / 60 );
        $segs -= $mins * 60;
        $microsegs = ( $segs - floor( $segs ) ) * 1000;
        $segs      = floor( $segs );

        return
            ( empty( $days ) ? "" : $days."d " ).
            ( empty( $hours ) ? "" : $hours."h " ).
            ( empty( $mins ) ? "" : $mins."m " ).
            $segs."s ".
            $microsegs."ms";
    }

    public function Start()
    {
        $this->startTime = getrusage();
    }

    public function End()
    {
        $this->endTime = getrusage();
        $this->compTime += $this->runTime( $this->endTime, $this->startTime, "utime" );
        $this->systemTime += $this->runTime( $this->endTime, $this->startTime, "stime" );
    }

    /**
     * @param $ru
     * @param $rus
     * @param $index
     */
    private function runTime( $ru, $rus, $index )
    {
        return ( $ru["ru_$index.tv_sec"] * 1000 + intval( $ru["ru_$index.tv_usec"] / 1000 ) )
             - ( $rus["ru_$index.tv_sec"] * 1000 + intval( $rus["ru_$index.tv_usec"] / 1000 ) );
    }

    public function __toString()
    {
        return "This process used ".$this->compTime." ms for its computations\n".
        "It spent ".$this->systemTime." ms in system calls\n";
    }
}

// // https://stackoverflow.com/questions/535020/tracking-the-script-execution-time-in-php
// // 1)
// $executionTime = new ExecutionTimeblooms();
// $executionTime->start();
// // code
// $executionTime->end();
// echo $executionTime;

// // 2)
// $executionTime = new ExecutionTimeblooms();
// $executionTime->go(); // if it's the case

// $arr = $class::fetch_price_feed( $feed_src );
// if ( $arr && is_array( $arr ) ) {
//     echo '<pre>';
//     var_export( $arr );
//     echo '</pre>';
//     // exit;
// }
// //The result will be in seconds and milliseconds.
// echo "<br />Took ".$executionTime->time()." to execute this code.";

https://www.if-not-true-then-false.com/2010/php-timing-class-class-for-measure-php-scripts-execution-time-and-php-web-page-load-time/

class Timingblooms {
	private $break;
	private $start_time;
	private $stop_time;

	// Constructor for Timingblooms class
	public function __construct($break = "") {
		$this->break = $break;
		// Set timezone
		date_default_timezone_set('UTC');
	}

	// Set start time
	public function start() {
		$this->start_time = microtime(true);
	}

	// Set stop/end time
	public function stop() {
		$this->stop_time = microtime(true);
	}

	// Returns time elapsed from start
	public function getElapsedTime() {
		return $this->getExecutionTime(microtime(true));
	}

	// Returns total execution time
	public function getTotalExecutionTime() {
		if (!$this->stop_time) {
			return false;
		}
		return $this->getExecutionTime($this->stop_time);
	}

	// Returns start time, stop time and total execution time
	public function getFullStats() {
		if (!$this->stop_time) {
			return false;
		}

		$stats = array();
		$stats['start_time'] = $this->getDateTime($this->start_time);
		$stats['stop_time'] = $this->getDateTime($this->stop_time);
		$stats['total_execution_time'] = $this->getExecutionTime($this->stop_time);

		return $stats;
	}

	// Prints time elapsed from start
	public function printElapsedTime() {
		echo $this->break . $this->break;
		echo "Elapsed time: " . $this->getExecutionTime(microtime(true));
		echo $this->break . $this->break;
	}

	// Prints total execution time
	public function printTotalExecutionTime() {
		if (!$this->stop_time) {
			return false;
		}

		echo $this->break . $this->break;
		echo "Total execution time: " . $this->getExecutionTime($this->stop_time);
		echo $this->break . $this->break;
	}

	// Prints start time, stop time and total execution time
	public function printFullStats() {
		if (!$this->stop_time) {
			return false;
		}

		echo $this->break . $this->break;
		echo "Script start date and time: " . $this->getDateTime($this->start_time);
		echo $this->break;
		echo "Script stop end date and time: " . $this->getDateTime($this->stop_time);
		echo $this->break . $this->break;
		echo "Total execution time: " . $this->getExecutionTime($this->stop_time);
		echo $this->break . $this->break;
	}

	// Format time to date and time
	private function getDateTime($time) {
		return date("Y-m-d H:i:s", $time);
	}

	// Get execution time by timestamp
	private function getExecutionTime($time) {
		return $time - $this->start_time;
	}
}
